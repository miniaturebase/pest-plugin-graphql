<?php

declare(strict_types=1);

namespace Pest\GraphQl;

use GraphQL\Type\Schema;
use Pest\Expectation;
use Pest\PendingObjects\TestCall;
use Pest\Plugin;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

Plugin::uses(GraphQl::class);

/**
 * @param string|Schema $document Schema file path or document instance
 *
 * @return Expectation
 */
function schema($document = '')
{
    return test()->schema($document);
}

expect()->extend('schema', function ($document = null) {
    return schema($document);
});

/**
 * Determine if the given document file or schema instance is valid GraphQL SDL.
 *
 * @param string|Schema $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function isValidSdl($document = '')
{
    return test()->isValidSdl($document);
}

expect()->extend('isValidSdl', function () {
    /**
     * @var Expectation $this
     */
    isValidSdl($this->value);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveUnion($document, string $type)
{
    return test()->toHaveUnion($document, $type);
}

expect()->extend('toHaveUnion', function (string $type) {
    /**
     * @var Expectation $this
     */
    toHaveUnion($this->value, $type);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveDirective($document, string $type)
{
    return test()->toHaveDirective($document, $type);
}

expect()->extend('toHaveDirective', function (string $type) {
    /**
     * @var Expectation $this
     */
    toHaveDirective($this->value, $type);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveType($document, string $type)
{
    return test()->toHaveType($document, $type);
}

expect()->extend('toHaveType', function (string $type) {
    /**
     * @var Expectation $this
     */
    toHaveType($this->value, $type);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveScalar($document, string $scalar)
{
    return test()->toHaveScalar($document, $scalar);
}

expect()->extend('toHaveScalar', function (string $scalar) {
    /**
     * @var Expectation $this
     */
    toHaveScalar($this->value, $scalar);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveEnum($document, string $enum)
{
    return test()->toHaveEnum($document, $enum);
}

expect()->extend('toHaveEnum', function (string $enum) {
    /**
     * @var Expectation $this
     */
    toHaveEnum($this->value, $enum);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveInput($document, string $input)
{
    return test()->toHaveInput($document, $input);
}

expect()->extend('toHaveInput', function (string $input) {
    /**
     * @var Expectation $this
     */
    toHaveInput($this->value, $input);

    return $this;
});

/**
 * @param string|Schema|null $document Schema file path or document instance
 *
 * @return TestCase|TestCall
 */
function toHaveInterface($document, string $interface)
{
    return test()->toHaveInterface($document, $interface);
}

expect()->extend('toHaveInterface', function (string $interface) {
    /**
     * @var Expectation $this
     */
    toHaveInterface($this->value, $interface);

    return $this;
});

/**
 * @param ResponseInterface $response A server response instance from a GraphQL API
 *
 * @return TestCase|TestCall
 */
function toBeGraphQlResponse($response)
{
    return test()->toBeGraphQlResponse($response);
}

expect()->extend('toBeGraphQlResponse', function () {
    /**
     * @var Expectation $this
     */
    toBeGraphQlResponse($this->value);

    return $this;
});

/**
 * @param ResponseInterface    $response A server response instance from a GraphQL API
 * @param array<string, mixed> $data     A blob of data to assert as the response
 *
 * @return TestCase|TestCall
 */
function toHaveData($response, array $data)
{
    return test()->toHaveData($response, $data);
}

expect()->extend('toHaveData', function (array $data) {
    /**
     * @var Expectation $this
     */
    toHaveData($this->value, $data);

    return $this;
});

/**
 * @param ResponseInterface $response A server response instance from a GraphQL API
 * @param array<int, array> $errors   A blob of errors to assert within the response
 *
 * @return TestCase|TestCall
 */
function toHaveErrors($response, array $errors)
{
    return test()->toHaveErrors($response, $errors);
}

expect()->extend('toHaveErrors', function (array $errors) {
    /**
     * @var Expectation $this
     */
    toHaveErrors($this->value, $errors);

    return $this;
});

/**
 * @param ResponseInterface $response A server response instance from a GraphQL API
 * @param string            $path     A dot-delimited string of fields ("dot notation") that point to a value in the response
 * @param mixed             $value    An optional value to assert at the given path, should it exist
 *
 * @return TestCase|TestCall
 */
function toHavePath($response, string $path, $value = null)
{
    return test()->toHavePath(...array_filter([
        $response,
        $path,
        (func_num_args() > 2) ? $value : null,
    ], static function ($arg): bool {
        return null !== $arg;
    }));
}

expect()->extend('toHavePath', function (string $path, $value = null) {
    toHavePath(...array_filter([
        $this->value,
        $path,
        (func_num_args() > 1) ? $value : null,
    ], static function ($arg): bool {
        return null !== $arg;
    }));

    return $this;
});
