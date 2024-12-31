<?php

use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\ProductType;

$conn = require 'D:/downloads2/backend_server/src/db.php';
$productsQuery = [
    'products' => [
        'type' => Type::listOf($productType), 
        'resolve' => function ($root, $args, $context) use ($conn) {
            $result = $conn->query('SELECT * FROM products');
            $products = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $products[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'inStock' => (bool) $row['inStock'],
                        'description' => $row['description'],
                        'category_id' => $row['category_id'],
                        'brand' => $row['brand'],
                        '__typename' => $row['__typename'],
                    ];
                }
                $result->free();
            }

            return $products;
        },
    ],
];

return $productsQuery;
