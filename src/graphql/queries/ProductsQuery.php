<?php

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\ProductType;

// $conn = require 'D:/downloads2/backend_server/src/db.php';
// $productsQuery = [
//     'products' => [
//         'type' => Type::listOf($productType), 
//         'resolve' => function ($root, $args, $context) use ($conn) {
//             $result = $conn->query('SELECT * FROM products');
//             $products = [];

//             if ($result) {
//                 while ($row = $result->fetch_assoc()) {
//                     $products[] = [
//                         'id' => $row['id'],
//                         'name' => $row['name'],
//                         'inStock' => (bool) $row['inStock'],
//                         'description' => $row['description'],
//                         'category_id' => $row['category_id'],
//                         'brand' => $row['brand'],
//                         '__typename' => $row['__typename'],
//                     ];
//                 }
//                 $result->free();
//             }

//             return $products;
//         },
//     ],
// ];

// return $productsQuery;


// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\ProductType;
// use App\Database\Connection;

// class ProductsQuery
// {
//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//     }

//     public function getProductsQuery()
//     {
//         return [
//             'products' => [
//                 'type' => Type::listOf(ProductType::getType()), 
//                 'resolve' => function ($root, $args, $context) {
//                     $result = $this->conn->query('SELECT * FROM products');
//                     $products = [];
                    
//                     if ($result) {
//                         while ($row = $result->fetch_assoc()) {
//                             $products[] = [
//                                 'id' => $row['id'],
//                                 'name' => $row['name'],
//                                 'inStock' => (bool) $row['inStock'],
//                                 'description' => $row['description'],
//                                 'category_id' => $row['category_id'],
//                                 'brand' => $row['brand'],
//                                 '__typename' => $row['__typename'],
//                             ];
//                         }
//                         $result->free();
//                     }

//                     return $products;
//                 },
//             ],
//         ];
//     }
// }
namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\ProductType;

class ProductsQuery
{
    private $conn;
    private $productType;

    public function __construct($conn, ProductType $productType)
    {
        $this->conn = $conn;
        $this->productType = $productType;
    }

    public function toGraphQL()
    {
        return [
            'products' => [
                'type' => Type::listOf($this->productType),
                'resolve' => function ($root, $args, $context) {
                    return $this->resolveProductsQuery();
                },
            ],
        ];
    }

    private function resolveProductsQuery()
    {
        $result = $this->conn->query('SELECT * FROM products');
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
    }
}
