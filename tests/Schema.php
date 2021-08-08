<?php

declare(strict_types=1);

use GraphQL\Utils\BuildSchema;

// dd(
//     BuildSchema::build(file_get_contents(__DIR__ . '/../schema.graphql'))->getType('Foo'),
// );

it('validates schemas')
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

it('asserts type existence')
    ->schema()
    ->toHaveType('Foo')
    ->toHaveType('Bar')
    ->not()
    ->toHaveType(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)))
    ->not()
    ->toHaveType('deprecated')
    ->not()
    ->toHaveType('BazUnion');

it('asserts union existence')
    ->schema()
    ->toHaveUnion('BazUnion')
    ->not()
    ->toHaveUnion(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)))
    ->not()
    ->toHaveUnion('Foo')
    ->not()
    ->toHaveUnion('deprecated');

it('asserts scalar existence')
    ->schema()
    ->toHaveScalar('Date')
    ->not()
    ->toHaveScalar(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)))
    ->not()
    ->toHaveScalar('deprecated');

it('asserts enum existence')
    ->schema()
    ->toHaveEnum('FooEnum')
    ->not()
    ->toHaveEnum(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)));

it('asserts input existence')
    ->schema()
    ->toHaveInput('FooInput')
    ->not()
    ->toHaveInput(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)));

it('asserts interface existence')
    ->schema()
    ->toHaveInterface('FooInterface')
    ->not()
    ->toHaveInterface(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)));

it('asserts directive existence')
    ->schema()
    ->toHaveDirective('deprecated')
    ->not()
    ->toHaveDirective(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)))
    ->not()
    ->toHaveDirective('Foo')
    ->not()
    ->toHaveDirective('BazUnion');
