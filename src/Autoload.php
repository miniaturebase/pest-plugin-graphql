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

expect()->extend('schema', function ($document = null): Expectation {
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
