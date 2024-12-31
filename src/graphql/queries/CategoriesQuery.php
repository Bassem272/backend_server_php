<?php


use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\CategoryType;
$conn = require __DIR__.'/../../db.php';


$categoriesQuery = [
    'categories' => [
        'type' => Type::listOf($categoryType),
        'resolve' => function ($root, $args, $context) use ($conn) {
            $result = $conn->query('SELECT * FROM categories');
            $categories = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                    ];
                }
                $result->free();
            }

            return $categories;
        },
    ],
];

return $categoriesQuery;
