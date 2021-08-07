<?php

declare(strict_types=1);

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

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

test('resolvers');
test('deferred');
