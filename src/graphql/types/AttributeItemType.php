<?php 

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

$attributeItemType = new ObjectType([
    'name' => 'AttributeItem',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'attribute_id' => ['type' => Type::nonNull(Type::string())],
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'displayValue' => ['type' => Type::string()],
        'value' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
    ]
]);
return $attributeItemType;