<?php

declare(strict_types=1);

namespace Pest\GraphQl;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
use Pest\Expectation;
use Pest\TestSuite;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
trait GraphQl
{
    /**
     * Get an instance of a GraphQL schema.
     *
     * @param string|Schema $document Schema file path or document instance
     *
     * @return Expectation
     */
    public function schema($document = '')
    {
        /**
         * @var self|TestCase $this
         */
        if ($document instanceof Schema) {
            return expect($document);
        }

        $source = empty($document)
            ? TestSuite::getInstance()->rootPath . '/schema.graphql'
            : $document;

        return expect(BuildSchema::build(
            is_file($source) ? file_get_contents($source) : $source
        ))->toBeInstanceOf(
            Schema::class
        );
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     */
    public function isValidSdl($document): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        $schema = $this->schema($document)->toHaveType('Query');

        try {
            $schema->value->assertValid();
        } catch (InvariantViolation $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     * @param string             $type     The name of the type as defined in the schema document
     */
    public function toHaveType($document, string $type): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($type)) {
            $namespace = explode('\\', $type);
            $type      = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($type))
                ->toBeInstanceOf(Type::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param ResponseInterface $response A server response instance from a GraphQL API
     */
    public function toBeGraphQlResponse($response): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        expect($response)
            ->toBeInstanceOf(ResponseInterface::class)
            ->and($response->getStatusCode())
            ->toBe(200)
            ->and($response->getReasonPhrase())
            ->toBe('OK')
            ->and($response->getHeaderLine('content-type'))
            ->toContain('json');

        $body = self::body($response);

        expect($body)->toBeJson();

        $json = json_decode($body, true);

        expect($json)
            // TODO: assert keys
            ->each(static function (Expectation $value): Expectation {
                return $value->toBeArray();
            })
            ->and(count($json))
            ->toBeLessThanOrEqual(2);

        return $this;
    }

    /**
     * @param ResponseInterface    $response A server response instance from a GraphQL API
     * @param array<string, mixed> $data     A blob of data to assert as the response
     */
    public function toHaveData($response, array $data): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        expect($response)->toBeGraphQlResponse();

        $body = self::body($response);

        expect($body)->toBeJson();

        $json = json_decode($body, true);

        expect($json)
            ->toHaveKey('data')
            ->and($json['data'])
            ->toEqualCanonicalizing($data);

        return $this;
    }

    /**
     * @param ResponseInterface $response A server response instance from a GraphQL API
     * @param array<int, array> $errors   A blob of errors to assert within the response
     */
    public function toHaveErrors($response, array $errors): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        expect($response)->toBeGraphQlResponse();

        $body = self::body($response);

        expect($body)->toBeJson();

        $json = json_decode($body, true);

        expect($json)
            ->toHaveKey('errors')
            ->and($json['errors'])
            ->toEqualCanonicalizing($errors);

        return $this;
    }

    /**
     * @param ResponseInterface $response A server response instance from a GraphQL API
     * @param string            $path     A dot-delimited string of fields ("dot notation") that point to a value in the response
     * @param mixed             $value    An optional value to assert at the given path, should it exist
     */
    public function toHavePath($response, string $path, $value = null): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        expect($response)->toBeGraphQlResponse();

        $body = self::body($response);

        expect($body)
            ->toBeJson()
            ->and(json_decode($body, true))
            ->toHaveKey(...array_filter([
                sprintf('data.%s', $path),
                (func_num_args() > 2) ? $value : null,
            ]));

        return $this;
    }

    /**
     * Read the contents and the response body. The body is rewound after
     * reading as to not cause any potential issues for user calls.
     *
     * @param ResponseInterface $response A PSR-7 response instance
     */
    private static function body(ResponseInterface $response): string
    {
        $body = $response->getBody();

        $body->rewind();

        $payload = $body->getContents();

        $body->rewind();

        return $payload;
    }
}
