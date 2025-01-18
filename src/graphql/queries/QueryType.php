<?php

// use GraphQL\Type\Definition\ObjectType;

// $productsQuery = require 'ProductsQuery.php';
// $productQuery = require 'ProductQuery.php';
// $categoriesQuery = require 'CategoriesQuery.php';


// $queryType = new ObjectType([
//     'name' => 'Query',
//     'fields' => array_merge(
//         $productsQuery,
//         $productQuery,
//         $categoriesQuery
//     ),
// ]);

// return $queryType;


// use App\GraphQL\Queries\ProductsQuery;
// use App\GraphQL\Queries\ProductQuery;
// use App\GraphQL\Queries\CategoriesQuery;
// use GraphQL\Type\Definition\ObjectType;

// $connection = new App\Database\Connection(); // or singleton dependency injection

// $productsQuery = new ProductsQuery($connection->getConnection());
// $productQuery = new ProductQuery($connection->getConnection());
// $categoriesQuery = new CategoriesQuery($connection->getConnection());

// $queryType = new ObjectType([
//     'name' => 'Query',
//     'fields' => array_merge(
//         $productsQuery->getProductsQuery(),
//         $productQuery->getProductQuery(),
//         $categoriesQuery->getCategoriesQuery()
//     ),
// ]);

// return $queryType;


// use GraphQL\Type\Definition\ObjectType;

// // Assuming the correct imports
// $productsQuery = require 'ProductsQuery.php';
// $productQuery = require 'ProductQuery.php';
// $categoriesQueryClass = new \App\GraphQL\Queries\CategoriesQuery($conn); // Instantiate CategoriesQuery
// $categoriesQuery = $categoriesQueryClass->getCategoriesQuery(); // Use the method to get query

// $queryType = new ObjectType([
//     'name' => 'Query',
//     'fields' => array_merge(
//         $productsQuery,
//         $productQuery,
//         $categoriesQuery // This is the new categoriesQuery after calling the class method
//     ),
// ]);

// return $queryType;

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
class QueryType
{
    private $conn;
    private $productQuery;
    private $productsQuery;
    private $categoriesQuery;

    public function __construct($conn, $productType, $priceType, $galleryType, $attributeType, $categoryType)
    {
        $this->conn = $conn;
        // Create and inject the ProductQuery here with all necessary dependencies
        $this->productQuery = new ProductQuery($conn, $productType, $priceType, $galleryType, $attributeType);
        $this->productsQuery = new ProductsQuery($conn, $productType);
        $this->categoriesQuery = new CategoriesQuery($conn, $categoryType);
    }

    public function toGraphQLObjectType(): ObjectType
    {
        // return new ObjectType([
        //     'name' => 'Query',
        //     'fields' => [
        //         'product' => [
        //             'type' => Type::listOf($this->productQuery->getProductType()), // GraphQL type
        //             'args' => [
        //                 'id' => ['type' => Type::string()],
        //                 'name' => ['type' => Type::string()],
        //             ],
        //             'resolve' => function ($root, $args, $context) {
        //                 return $this->productQuery->resolveProductQuery($args);
        //             }
        //         ]
        //     ]
        // ]);
        return new ObjectType([
            'name' => 'Query',
            'fields' => array_merge(
                $this->productQuery->toGraphQL(),
                $this->productsQuery->toGraphQL(),
                $this->categoriesQuery->toGraphQL()
            ),
        ]);
    }
}
