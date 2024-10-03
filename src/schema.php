<?php

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
require_once __DIR__ . '/../vendor/autoload.php';
$conn = require 'db.php';
// Define the ProductType
$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::nonNull(Type::string())],
        // 'price' => ['type' => Type::float()],
        'description' => ['type' => Type::string()],
    ],
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
                $result = $conn->query('SELECT id, name, description FROM products');
                $products = [];

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $products[] = [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            // 'price' => $row['price'],
                            'description' => $row['description'],
                        ];
                    }
                    $result->free(); // Free the result set
                }

                return $products;
            },
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
