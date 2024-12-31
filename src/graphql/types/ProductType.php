<?php 

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
$conn = require __DIR__.'/../../db.php';

$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::nonNull(Type::string())],
        'inStock' => ['type' => Type::nonNull(Type::boolean())],
        'description' => ['type' => Type::string()],
        'category_id' => ['type' => Type::string()],
        'brand' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
        'price' => [
            'type' => Type::listOf($priceType), 
            'resolve' => function ($product, $args, $context) use ($conn) {
                $query = "SELECT * FROM prices WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $prices = [];
                while ($price = $result->fetch_assoc()) {
                    $prices[] = [
                        'product_id' => $price['product_id'],  
                        'amount' => $price['amount'],
                        'currency_label' => $price['currency_label'],
                        'currency_symbol' => $price['currency_symbol'],
                        '__typename' => $price['__typename'],
                    ];
                }

                return $prices;  
            }
        ],
        'gallery' => [
            'type' => Type::ListOf($galleryType),
            'resolve' => function ($product, $args, $context) use ($conn) {
                $query = 'SELECT * FROM product_gallery WHERE product_id = ?';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('s', $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $gallery = [];
                while ($row = $result->fetch_assoc()) {
                    $gallery[] = [
                        'product_id' => $row['product_id'],
                        'image_url' => $row['image_url'],
                    ];
                }
                return $gallery;
            }
        ],
        'attributes' => [
            'type' => Type::listOf($attributeType),
            'resolve' => function ($product, $args, $context) use ($conn) {
                $query = "SELECT * FROM attributes WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $attributes = [];
                while ($attribute = $result->fetch_assoc()) {
                    $attributes[] = $attribute;
                }
                return $attributes;
            }
        ]
    ],
]);

return $productType;