<?php

use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\productType;

require 'D:/downloads2/backend_server/src/db.php';

$conn = require __DIR__.'/../../db.php';

$productQuery = [
    'product' => [
        'type' => Type::listOf($productType), // Return a list of products
        'args' => [
            'id' => ['type' => Type::string()],
            'name' => ['type' => Type::string()],
        ],
        'resolve' => function ($root, $args, $context) use ($conn) {
            $query = "SELECT * FROM products";
            $params = [];
            
            if (!empty($args['id'])) {
                $query .= " WHERE id = ?";
                $params[] = $args['id'];
            } elseif (!empty($args['name'])) {
                $query .= " WHERE name = ?";
                $params[] = $args['name'];
            } else {
                // Return an empty list when no filter is applied
                return [];
            }

            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new \Exception('Failed to prepare the SQL query: ' . $conn->error);
            }

            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch results as an array of products
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            return $products; // Return an array of products
        },
    ],
];

return $productQuery;
