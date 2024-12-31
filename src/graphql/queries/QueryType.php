<?php

use GraphQL\Type\Definition\ObjectType;

$productsQuery = require 'ProductsQuery.php';
$productQuery = require 'ProductQuery.php';
$categoriesQuery = require 'CategoriesQuery.php';


$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => array_merge(
        $productsQuery,
        $productQuery,
        $categoriesQuery
    ),
]);

return $queryType;
