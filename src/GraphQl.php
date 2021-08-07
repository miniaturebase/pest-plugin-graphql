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
     * @param string|Schema|null $document File path to a GraphQL schema document
     */
    public function schema($document = null): Expectation
    {
        if ($document instanceof Schema) {
            return expect($document);
        }

        $source = $document ?? TestSuite::getInstance()->rootPath . '/schema.graphql';

        return expect(BuildSchema::build(is_file($source) ? file_get_contents($source) : $source))
            ->toBeInstanceOf(Schema::class);
    }

    public function isValidSdl($document): TestCase
    {
        try {
            /** @var self|TestCase $this */
            $this->schema($document)
                ->toHaveType('Query')
                ->toHaveType('Mutation')
                ->assertValid();
        } catch (InvariantViolation $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    public function toHaveType($document, string $type): TestCase
    {
        try {
            /** @var self|TestCase $this */
            $this->schema($document)
                ->toBeInstanceOf(Schema::class)
                ->getType($type)
                ->toBeInstanceOf(Type::class);
        } catch (Error $caught) {
            throw new ExpectationFailedException($caught->getMessage(), null, $caught);
        }

        return $this;
    }

    public function toBeGraphQlResponse($response): TestCase
    {
        /** @var self|TestCase $this */
        expect($response)
            ->toBeInstanceOf(ResponseInterface::class)
            ->getStatusCode()
            ->toBe(200)
            ->getReasonPhrase()
            ->toBe('OK')
            ->getHeaderLine('content-type')
            ->toContain('json');

        /** @var ResponseInterface $response */
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

    public function toHaveData($response, array $data): TestCase
    {
        /**
         * @var self|TestCase $this
         * @var ResponseInterface $response
         */
        $body = $response->getBody();

        $body->rewind();

        expect($body->getContents())
            ->json()
            ->toHaveKey('data')
            ->data
            ->toBe($data);

        return $this;
    }

    public function toHaveErrors($response, array $errors): TestCase
    {
        /**
         * @var self|TestCase $this
         * @var ResponseInterface $response
         */
        $body = $response->getBody();

        $body->rewind();

        expect($body->getContents())
            ->json()
            ->toHaveKey('errors')
            ->errors
            ->toBe($errors);

        return $this;
    }
}
