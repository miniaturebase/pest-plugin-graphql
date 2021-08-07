<?php

declare(strict_types=1);

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
    ->not()
    ->toHaveType(substr(base64_encode(random_bytes(8)), 0, rand(1, 64)));

it('asserts directive existence');
