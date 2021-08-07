<?php

declare(strict_types=1);

namespace Pest\GraphQl;

use GraphQL\Type\Schema;
use Pest\Expectation;
use Pest\PendingObjects\TestCall;
use Pest\Plugin;
use PHPUnit\Framework\TestCase;

Plugin::uses(GraphQl::class);

/**
 * @return Expectation
 */
function schema($document = null)
{
    return test()->schema($document);
}

expect()->extend('schema', function ($document = null) {
    return schema($document);
});

/**
 * Determine if the given document file or schema instance is valid GraphQL SDL.
 *
 * @param null|string|Schema $document
 * @return TestCase|TestCall
 */
function isValidSdl($document = null)
{
    return test()->isValidSdl($document);
}

expect()->extend('isValidSdl', function () {
    isValidSdl($this->value);

    return $this;
});


/**
 * @return TestCase|TestCall
 */
function toHaveType($document, string $type)
{
    return test()->toHaveType($document, $type);
}

expect()->extend('toHaveType', function (string $type) {
    toHaveType($this->value, $type);

    return $this;
});

/**
 * @return TestCase|TestCall
 */
function toBeGraphQlResponse($response)
{
    return test()->toBeGraphQlResponse($response);
}

expect()->extend('toBeGraphQlResponse', function () {
    toBeGraphQlResponse($this->value);

    return $this;
});

/**
 * @return TestCase|TestCall
 */
function toHaveData($response, array $data)
{
    return test()->toHaveData($response, $data);
}

expect()->extend('toHaveData', function (array $data) {
    toHaveData($this->value, $data);

    return $this;
});

/**
 * @return TestCase|TestCall
 */
function toHaveErrors($response, array $errors)
{
    return test()->toHaveErrors($response, $errors);
}

expect()->extend('toHaveErrors', function (array $errors) {
    toHaveErrors($this->value, $errors);

    return $this;
});
