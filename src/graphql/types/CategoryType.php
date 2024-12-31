<?php 

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

$categoryType = new ObjectType([
    'name' => 'categories',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::nonNull(Type::string())],
    ]
]);
return $categoryType;