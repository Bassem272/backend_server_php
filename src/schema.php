<?php


use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Schema;
use App\Database\Connection;

$connection = new Connection();
$conn = $connection->getConnection();

require_once __DIR__ . '/../vendor/autoload.php';
// $conn = require 'db.php';



// $attributeItemType = require __DIR__ ."/graphql/types/AttributeItemType.php";
use App\GraphQL\Types\AttributeType;
use App\GraphQL\Types\AttributeItemType;

$attributeItemType = new AttributeItemType();
$attributeType = new AttributeType($attributeItemType, $conn);



use App\GraphQL\Types\GalleryType;

$galleryType = new GalleryType();

use App\GraphQL\Types\PriceType;

$priceType = new PriceType();

use App\GraphQL\Types\CategoryType;
$categoryType = new CategoryType(); 

use App\GraphQL\Types\ProductType;

$productType = new ProductType($conn, $priceType, $galleryType, $attributeType);
use App\GraphQL\Queries\QueryType; 


// Create the query type and pass the created productType
$queryType = new QueryType($conn, $productType, $priceType, $galleryType, $attributeType,$categoryType);


use App\GraphQL\Mutations\MutationType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\OrderItemInputType;

// Instantiate the OrderType
$orderType = (new OrderType())->getType();
$orderItemInputType = (new OrderItemInputType())->getType();
use App\GraphQL\Mutations;
$mutationType = new MutationType($conn, $orderType, $orderItemInputType);




// Create the schema
return new Schema([
    'query' => $queryType->toGraphQLObjectType(),
    'mutation' =>$mutationType->getType(),
]);
