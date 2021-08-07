<?php

use GraphQL\Utils\BuildSchema;
use Laminas\Diactoros\Response\JsonResponse;
use function Pest\GraphQl\isValidSdl;
use function Pest\GraphQl\schema;
use function Pest\GraphQl\toBeGraphQlResponse;
use function Pest\GraphQl\toHaveData;
use function Pest\GraphQl\toHaveErrors;
use function Pest\GraphQl\toHaveType;

schema();
isValidSdl();
toHaveType(BuildSchema::build(file_get_contents(__DIR__ . '/../schema.graphql')), 'Query');
toBeGraphQlResponse(new JsonResponse(['data' => []]));
toHaveData(new JsonResponse(['data' => ['foo' => false]]), ['foo' => false]);
toHaveErrors(new JsonResponse(['errors' => []]), []);
