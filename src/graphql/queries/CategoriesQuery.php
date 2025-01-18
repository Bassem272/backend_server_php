<?php


// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\CategoryType;
// $conn = require __DIR__.'/../../db.php';


// $categoriesQuery = [
//     'categories' => [
//         'type' => Type::listOf($categoryType),
//         'resolve' => function ($root, $args, $context) use ($conn) {
//             $result = $conn->query('SELECT * FROM categories');
//             $categories = [];

//             if ($result) {
//                 while ($row = $result->fetch_assoc()) {
//                     $categories[] = [
//                         'id' => $row['id'],
//                         'name' => $row['name'],
//                     ];
//                 }
//                 $result->free();
//             }

//             return $categories;
//         },
//     ],
// ];

// return $categoriesQuery;


// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\CategoryType;
// use App\Database\Connection;

// class CategoriesQuery
// {
//     private $conn;

//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//     }

//     public function getCategoriesQuery()
//     {
//         return [
//             'categories' => [
//                 'type' => Type::listOf(CategoryType::getType()),
//                 'resolve' => function ($root, $args, $context) {
//                     $result = $this->conn->query('SELECT * FROM categories');
//                     $categories = [];

//                     if ($result) {
//                         while ($row = $result->fetch_assoc()) {
//                             $categories[] = [
//                                 'id' => $row['id'],
//                                 'name' => $row['name'],
//                             ];
//                         }
//                         $result->free();
//                     }

//                     return $categories;
//                 },
//             ],
//         ];
//     }
// }

// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\CategoryType;
// use App\Database\Connection;

// class CategoriesQuery
// {
//     private $conn;
//     private $categoryType;

//     // Constructor to instantiate connection and categoryType
//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//         $this->categoryType = new CategoryType(); // Instantiate CategoryType
//     }

//     // Method to get the categories query
//     public function getCategoriesQuery()
//     {
//         return [
//             'categories' => [
//                 'type' => Type::listOf($this->categoryType), // Use instantiated categoryType here
//                 'resolve' => function ($root, $args, $context) {
//                     return $this->resolveCategoriesQuery();
//                 },
//             ],
//         ];
//     }

//     // Resolving the query for categories from the database
//     private function resolveCategoriesQuery()
//     {
//         // Run the query
//         $result = $this->conn->query('SELECT * FROM categories');
//         $categories = [];

//         if ($result) {
//             while ($row = $result->fetch_assoc()) {
//                 $categories[] = [
//                     'id' => $row['id'],
//                     'name' => $row['name'],
//                 ];
//             }
//             $result->free();
//         }

//         return $categories;
//     }
// }

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\CategoryType;

class CategoriesQuery
{
    private $conn;
    private $categoryType;

    public function __construct($conn, CategoryType $categoryType)
    {
        $this->conn = $conn;
        $this->categoryType = $categoryType;
    }

    public function toGraphQL()
    {
        return [
            'categories' => [
                'type' => Type::listOf($this->categoryType),
                'resolve' => function ($root, $args, $context) {
                    return $this->resolveCategoriesQuery();
                },
            ],
        ];
    }

    private function resolveCategoriesQuery()
    {
        $result = $this->conn->query('SELECT * FROM categories');
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
    }
}
