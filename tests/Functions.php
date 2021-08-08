<?php

use GraphQL\Utils\BuildSchema;
use Laminas\Diactoros\Response\JsonResponse;
use function Pest\GraphQl\isValidSdl;
use function Pest\GraphQl\schema;
use function Pest\GraphQl\toBeGraphQlResponse;
use function Pest\GraphQl\toHaveData;
use function Pest\GraphQl\toHaveDirective;
use function Pest\GraphQl\toHaveEnum;
use function Pest\GraphQl\toHaveErrors;
use function Pest\GraphQl\toHaveInput;
use function Pest\GraphQl\toHaveInterface;
use function Pest\GraphQl\toHavePath;
use function Pest\GraphQl\toHaveScalar;
use function Pest\GraphQl\toHaveType;
use function Pest\GraphQl\toHaveUnion;

schema();
isValidSdl();

$schema = BuildSchema::build(file_get_contents(__DIR__ . '/../schema.graphql'));

toHaveDirective($schema, 'deprecated');
toHaveEnum($schema, 'FooEnum');
toHaveInput($schema, 'FooInput');
toHaveInterface($schema, 'FooInterface');
toHaveScalar($schema, 'Date');
toHaveType($schema, 'Query');
toHaveUnion($schema, 'BazUnion');

toBeGraphQlResponse(new JsonResponse(['data' => []]));
toHaveData(new JsonResponse(['data' => ['foo' => false]]), ['foo' => false]);
toHaveErrors(new JsonResponse(['errors' => []]), []);
toHavePath(new JsonResponse(['data' => ['foo' => ['bar' => ['baz' => 1]]]]), 'foo.bar.baz');
