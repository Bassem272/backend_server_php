<?php

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
require_once __DIR__ . '/../vendor/autoload.php';
$conn = require 'db.php';
// Define the ProductType
// --------------------------------------------------------------------------------------------------------+
// | products | CREATE TABLE `products` (
//   `id` varchar(255) NOT NULL,
//   `name` varchar(255) DEFAULT NULL,
//   `inStock` tinyint(1) DEFAULT NULL,
//   `description` text,
//   `category_id` varchar(255) DEFAULT NULL,
//   `brand` varchar(255) DEFAULT NULL,
//   `__typename` varchar(255) DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 |
// +----------+-----------
$galleryType = new ObjectType([
    'name'=> 'Gallery',
    'fields'=> [
        'product_id'=> ['type' => Type::nonNull(Type::string())] ,
        'image_url' => ['type' => Type::nonNull(Type::string())],
    ],
]);

$priceType = new ObjectType([
    'name' => 'Price',
    'fields' => [
        'product_id' => ['type' => Type::nonNull(Type::string())],  // Add product_id field
        'amount' => ['type' => Type::float()],
        'currency_label' => ['type' => Type::nonNull(Type::string())],
        'currency_symbol' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
    ],
]);
$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::nonNull(Type::string())],
        'inStock' => ['type' => Type::nonNull(Type::boolean())],
        // 'price' => ['type' => Type::float()],
        'description' => ['type' => Type::string()],
        'category_id' => ['type'=> Type::string()],
        'brand' => [ 'type'=> Type::string()],
        '__typename' => ['type'=> Type::string()],
        'price' => [
            'type' => Type::listOf($priceType),  // List of prices for different currencies
            'resolve' => function ($product, $args, $context) use ($conn) {
                // Query prices table to fetch all prices for this product
                $query = "SELECT * FROM prices WHERE product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $prices = [];
                while ($price = $result->fetch_assoc()) {
                    $prices[] = [
                        'product_id' => $price['product_id'],  // Include product_id in the result
                        'amount' => $price['amount'],
                        'currency_label' => $price['currency_label'],
                        'currency_symbol' => $price['currency_symbol'],
                        '__typename' => $price['__typename'],
                    ];
                }
                
                return $prices;  // Return the list of prices
            }
        ],
        'gallery' => [
            'type' => Type::ListOf($galleryType),
            'resolve' => function ($product, $args, $context) use ($conn)
            {
                $query = 'SELECT * FROM product_gallery WHERE product_id = ?';
                $stmt = $conn -> prepare($query);
                $stmt->bind_param('s', $product['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                $gallery = [];
               
                    while ($row = $result->fetch_assoc()){
                        $gallery[] = [
                            'product_id'=> $row['product_id'],
                            'image_url' => $row['image_url'],
                        ];
                    }
                    
                
                return $gallery;
            }
        ]
    ],
]);

$categoryType = new ObjectType([
    'name'=> 'categories',
    'fields' => [
        'id'=> ['type'=> Type::nonNull(Type::string())],
        'name'=> ['type'=> Type::nonNull(Type::string())],
    ]
    ]);


    
// Define the Query type with the updated products field
$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echo' => [
            'type' => Type::string(),
            'args' => [
                'message' => Type::nonNull(Type::string()),
            ],
            'resolve' => function ($root, $args) {
                return 'You said: ' . $args['message'];
            },
        ],
        'sum' => [
            'type' => Type::int(),
            'args' => [
                'x' => Type::nonNull(Type::int()),
                'y' => Type::nonNull(Type::int()),
            ],
            'resolve' => function ($root, $args) {
                return $args['x'] + $args['y'];
            },
        ],
        'products' => [
            'type' => Type::listOf($productType), // Use the custom ProductType here
            'resolve' => function () use ($conn) {
                // Fetch products from the database
                $result = $conn->query('SELECT id, name, description, brand FROM products');
                $products = [];

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $products[] = [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            // 'price' => $row['price'],
                            'inStock' =>  (bool) $row['inStock'],
                            'description' => $row['description'],
                            'category_id' => $row['category_id'],
                            'brand' => $row['brand'],
                            '__typename' => $row['__typename'],
                        ];
                    }
                    $result->free(); // Free the result set
                }
                return $products;
            },
        ],
        'categories'=> [
            'type' => Type::listOf($categoryType),
            'resolve' => function ($root , $args) use ($conn){
                $result = $conn->query('SELECT * FROM categories');
                $categories = [];
                if ($result) {
                    while ($row = $result -> fetch_assoc()){
                    $categories[] = [
                            'id' => $row['id'],
                            'name'=> $row['name'],
                        ];
                    }
                    $result->free();
                }
                return $categories;
            }

        ],
        'multi' => [
            'type' => Type::listOf(Type::int()), // Assuming multi returns a list of integers
            'args' => [
                'numbers' => Type::nonNull(Type::listOf(Type::int())),
            ],
            'resolve' => function ($root, $args) {
                return array_map(function ($n) {
                    return $n * 2; // Example: doubling the numbers in the list
                }, $args['numbers']);
            },
        ],
    ],
]);

// Define the Mutation type (kept as-is for now)
$mutationType = new ObjectType([
    'name' => 'Mutation',
    'fields' =>  [ 
        'placeOrder' => [
            'type' => Type::nonNull(Type::string()), 
            'args' => [
                'input' => Type::nonNull(Type::string()), 
            ],
            'resolve' => function ($root, $args) {
                return 'Order placed with input: ' . $args['input'];
            },
        ],
    ],
]);

// Create the schema
return new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);

    
