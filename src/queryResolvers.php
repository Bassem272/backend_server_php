<?php

use GraphQL\Type\Definition\Type;
$conn = require 'db.php'; // Make sure this correctly returns the connection

return [
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
            'type' => Type::listOf(Type::string()), // Adjust type according to your product structure
            'resolve' => function () use ($conn) {
                $result = $conn->query('SELECT name FROM products'); // Assuming 'name' is a field in your products table
                $products = [];

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $products[] = $row['name']; // Collect the product names
                    }
                    $result->free(); // Free the result set
                }

                return $products;
            },
        ],
    ],
];
