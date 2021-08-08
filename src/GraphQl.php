<?php

declare(strict_types=1);

namespace Pest\GraphQl;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\Directive;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\UnionType;
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
     * @param string|Schema|null $document  Schema file path or document instance
     * @param string             $interface The name of the interface as defined in the schema document
     */
    public function toHaveInterface($document, string $interface): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($interface)) {
            $namespace      = explode('\\', $interface);
            $interface      = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($interface))
                ->toBeInstanceOf(Type::class);
        } catch (Error $caught) {
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
                ->toBeInstanceOf(ObjectType::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document  Schema file path or document instance
     * @param string             $directive The name of the directive as defined in the schema document
     */
    public function toHaveDirective($document, string $directive): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($directive)) {
            $namespace      = explode('\\', $directive);
            $directive      = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getDirective($directive))
                ->toBeInstanceOf(Directive::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     * @param string             $union    The name of the union as defined in the schema document
     */
    public function toHaveUnion($document, string $union): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($union)) {
            $namespace      = explode('\\', $union);
            $union          = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($union))
                ->toBeInstanceOf(UnionType::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     * @param string             $input    The name of the input as defined in the schema document
     */
    public function toHaveInput($document, string $input): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($input)) {
            $namespace      = explode('\\', $input);
            $input          = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($input))
                ->toBeInstanceOf(InputType::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     * @param string             $enum     The name of the enum as defined in the schema document
     */
    public function toHaveEnum($document, string $enum): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($enum)) {
            $namespace      = explode('\\', $enum);
            $enum           = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($enum))
                ->toBeInstanceOf(EnumType::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    /**
     * @param string|Schema|null $document Schema file path or document instance
     * @param string             $scalar   The name of the scalar as defined in the schema document
     */
    public function toHaveScalar($document, string $scalar): TestCase
    {
        /**
         * @var self|TestCase $this
         */
        if (class_exists($scalar)) {
            $namespace      = explode('\\', $scalar);
            $scalar         = end($namespace);
        }

        $schema = $this->schema($document);

        try {
            $schema
                ->toBeInstanceOf(Schema::class)
                ->and($schema->value->getType($scalar))
                ->toBeInstanceOf(ScalarType::class);
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

        expect(count($json))->toBeLessThanOrEqual(2);

        foreach (array_values($json) as $node) {
            expect($node)->toBeArray();
        }

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

        expect($body)->toBeJson();

        $this->toHaveKey(json_decode($body, true), ...array_filter([
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

    /**
     * @see https://github.com/pestphp/pest/blob/d1a9e0bbe31dcb266690dc4e7517e2cb73d5827a/src/Support/Arr.php
     *
     * @param array<mixed> $array
     * @param string|int   $key
     */
    private static function has(array $array, $key): bool
    {
        $key = (string) $key;

        if (array_key_exists($key, $array)) {
            return true;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @see https://github.com/pestphp/pest/blob/d1a9e0bbe31dcb266690dc4e7517e2cb73d5827a/src/Support/Arr.php
     *
     * @param array<mixed> $array
     * @param string|int   $key
     * @param null         $default
     *
     * @return array|mixed|null
     */
    private static function get(array $array, $key, $default = null)
    {
        $key = (string) $key;

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Asserts that the value array has the provided $key.
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @see https://github.com/pestphp/pest/blob/30f39f18507b5d0ec69d738f197f99b490d9fce6/src/Expectation.php#L571-L600
     */
    private function toHaveKey($actual, $key, $value = null)
    {
        /**
         * @var self|TestCase $this
         */
        if (is_object($actual) && method_exists($actual, 'toArray')) {
            $array = $actual->toArray();
        } else {
            $array = (array) $actual;
        }

        try {
            $this->assertTrue(self::has($array, $key));

            /* @phpstan-ignore-next-line  */
        } catch (ExpectationFailedException $exception) {
            throw new ExpectationFailedException("Failed asserting that an array has the key '$key'", $exception->getComparisonFailure());
        }

        if (func_num_args() > 2) {
            $this->assertEquals($value, self::get($array, $key));
        }

        return $this;
    }
}
