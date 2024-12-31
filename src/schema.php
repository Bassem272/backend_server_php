<?php


use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Schema;

require_once __DIR__ . '/../vendor/autoload.php';
$conn = require 'db.php';



$attributeItemType = require __DIR__ ."/graphql/types/AttributeItemType.php";
$attributeType = require __DIR__ ."/graphql/types/AttributeType.php";
$galleryType = require __DIR__ ."/graphql/types/GalleryType.php";
$priceType = require __DIR__ ."/graphql/types/PriceType.php";
$priceType = require __DIR__ ."/graphql/types/PriceType.php";
$categoryType = require __DIR__ ."/graphql/types/CategoryType.php";
$productType = require __DIR__ ."/graphql/types/ProductType.php";
$queryType = require __DIR__ ."/graphql/queries/QueryType.php";

$orderItemInputType = require __DIR__ ."/graphql/types/OrderItemInputType.php";
$orderType = require __DIR__ ."/graphql/types/OrderType.php";
$mutationType = require __DIR__ ."/graphql/queries/MutationType.php";


// Create the schema
return new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);
