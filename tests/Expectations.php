<?php

declare(strict_types=1);

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

it('reads schemas')
    ->schema()
    ->isValidSdl()
    ->schema(__DIR__ . '/../schema.graphql')
    ->isValidSdl()
    ->schema(<<<'GRAPHQL'
type Query
type Mutation
GRAPHQL)
    ->not()
    ->isValidSdl();

it('checks types')
    ->schema()
    ->toHaveType('Foo')
    ->not()
    ->toHaveType(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)));

it('asserts response data')
    ->expect(new JsonResponse(['data' => ['foo' => true]]))
    ->toBeGraphQlResponse()
    ->toHaveData(['foo' => true])
    ->not()
    ->toHaveData(['foo' => false])
    ->and(new HtmlResponse('<p>Hello, World!</p>'))
    ->not()
    ->toBeGraphQlResponse();

it('asserts response errors')
    ->expect(new JsonResponse([
        'errors' => [
            [
                'message'   => 'asdf',
                'locations' => [['line' => 6, 'column' => 7]],
                'path'      => ['foo'],
            ],
        ],
    ]))
    ->toBeGraphQlResponse()
    ->toHaveErrors([
        [
            'message'   => 'asdf',
            'locations' => [['line' => 6, 'column' => 7]],
            'path'      => ['foo'],
        ],
    ])
    ->not()
    ->toHaveErrors([
        [
            'message'   => 'qwerty',
            'locations' => [['line' => 6, 'column' => 7]],
            'path'      => ['foo'],
        ],
    ]);
