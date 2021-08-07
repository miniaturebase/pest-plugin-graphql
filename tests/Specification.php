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
            'locations' => [['line' => 6, 'column' => 7]],
            'message'   => 'asdf',
            'path'      => ['foo'],
        ],
    ])
    ->not()
    ->toHaveErrors([
        [
            'locations' => [['line' => 6, 'column' => 7]],
            'message'   => 'qwerty',
            'path'      => ['foo'],
        ],
    ]);

test('path checks')
    ->expect(new JsonResponse(['data' => ['foo' => []]]))
    ->toHavePath('foo')
    ->and(new JsonResponse(['data' => ['jah' => ['ith' => ['ber' => 'enigma']]]]))
    ->toHavePath('jah.ith.ber', 'enigma')
    ->not()
    ->toHavePath('foo.bar.baz')
    ->not()
    ->toHavePath('jah.ith.ber', 'ligma');

test('resolvers');
test('deferred');
