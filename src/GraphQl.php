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
     */
    public function schema($document = ''): Expectation
    {
        if ($document instanceof Schema) {
            return expect($document);
        }

        $source = empty($document) ? TestSuite::getInstance()->rootPath . '/schema.graphql' : $document;

        return expect(BuildSchema::build(is_file($source) ? file_get_contents($source) : $source))
            ->toBeInstanceOf(Schema::class);
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     */
    public function isValidSdl($document): TestCase
    {
        try {
            /**
             * @var self|TestCase $this
             */
            $this->schema($document)
                ->toHaveType('Query')
                ->assertValid();
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
        if (class_exists($type)) {
            $namespace = explode('\\', $type);
            $type      = end($namespace);
        }

        try {
            /**
             * @var self|TestCase $this
             */
            $this->schema($document)
                ->toBeInstanceOf(Schema::class)
                ->getType($type)
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
            ->getStatusCode()
            ->toBe(200)
            ->getReasonPhrase()
            ->toBe('OK')
            ->getHeaderLine('content-type')
            ->toContain('json');

        $body = $response->getBody();

        $body->rewind();

        $payload = $body->getContents();

        expect($payload)
            ->json()
            ->each(function (Expectation $value): Expectation {
                return $value->toBeArray();
            })
            ->and(count(json_decode($payload, true)))
            ->toBeLessThanOrEqual(2)
            ->and($response);

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
        $body = $response->getBody();

        $body->rewind();

        expect($body->getContents())
            ->json()
            ->toHaveKey('data')
            ->data
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
        $body = $response->getBody();

        $body->rewind();

        expect($body->getContents())
            ->json()
            ->toHaveKey('errors')
            ->errors
            ->toEqualCanonicalizing($errors);

        return $this;
    }

    public function toHavePath($response, string $path, $value = null): TestCase
    {
        expect($response)->toBeGraphQlResponse();

        /**
         * @var self|TestCase $this
         */
        $body = $response->getBody();

        $body->rewind();

        expect($body->getContents())
            ->json()
            ->toHaveKey(...array_filter([
                sprintf('data.%s', $path),
                (func_num_args() > 2) ? $value : null,
            ]));

        return $this;
    }
}
