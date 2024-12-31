<?php 

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
$conn = require __DIR__.'/../../db.php';


$attributeType = new ObjectType([
    'name' => 'Attribute',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::string()],
        'type' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
        'attribute_items' => [
            'type' => Type::listOf($attributeItemType),  // List of attribute items
            'resolve' => function ($attribute, $args, $context) use ($conn) {
                // Query the attribute_items table for the given attribute
                $query = "SELECT * FROM attribute_items WHERE attribute_id = ? AND product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $attribute['id'], $attribute['product_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                $attributeItems = [];
                while ($item = $result->fetch_assoc()) {
                    $attributeItems[] = [
                        'id' => $item['id'],
                        'attribute_id' => $item['attribute_id'],
                        'product_id' => $item['product_id'],
                        'displayValue' => $item['displayValue'],
                        'value' => $item['value'],
                        '__typename' => $item['__typename'],
                    ];
                }
                return $attributeItems;
            }
        ]
    ]
]);

return $attributeType;