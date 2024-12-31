<?php
// src/GraphQL/schema.php
// namespace App\GraphQL;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Schema;

require_once __DIR__ . '/../vendor/autoload.php';
$conn = require 'db.php';

// Define the ProductType
// --------------------------------------------------------------------------------------------------------+
// | products | CREATE TABLE `products` (
//   `id` varchar(255) NOT NULL,
//   `name` varchar(255) DEFAULT NULL,
//   `inStock` tinyint(1) DEFAULT NULL,
//   `description` text,
//   `category_id` varchar(255) DEFAULT NULL,
//   `brand` varchar(255) DEFAULT NULL,
//   `__typename` varchar(255) DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 |
// +----------+-----------

$attributeItemType = new ObjectType([
    'name' => 'AttributeItem',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'attribute_id' => ['type' => Type::nonNull(Type::string())],
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'displayValue' => ['type' => Type::string()],
        'value' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
    ]
]);

$attributeType = new ObjectType([
    'name' => 'Attribute',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::string())],
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::string()],
        'type' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
        'attribute_items' => [
            'type' => Type::listOf($attributeItemType),  // List of attribute items
            'resolve' => function ($attribute, $args, $context) use ($conn) {
                // Query the attribute_items table for the given attribute
                $query = "SELECT * FROM attribute_items WHERE attribute_id = ? AND product_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $attribute['id'], $attribute['product_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                $attributeItems = [];
                while ($item = $result->fetch_assoc()) {
                    $attributeItems[] = [
                        'id' => $item['id'],
                        'attribute_id' => $item['attribute_id'],
                        'product_id' => $item['product_id'],
                        'displayValue' => $item['displayValue'],
                        'value' => $item['value'],
                        '__typename' => $item['__typename'],
                    ];
                }
                return $attributeItems;
            }
        ]
    ]
]);

$galleryType = new ObjectType([
    'name' => 'Gallery',
    'fields' => [
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'image_url' => ['type' => Type::nonNull(Type::string())],
    ],
]);

$priceType = new ObjectType([
    'name' => 'Price',
    'fields' => [
        'product_id' => ['type' => Type::nonNull(Type::string())],  // Add product_id field
        'amount' => ['type' => Type::float()],
        'currency_label' => ['type' => Type::nonNull(Type::string())],
        'currency_symbol' => ['type' => Type::string()],
        '__typename' => ['type' => Type::string()],
    ],
]);
// $productType = new ObjectType([
//     'name' => 'Product',
//     'fields' => [
//         'id' => ['type' => Type::nonNull(Type::string())],
//         'name' => ['type' => Type::nonNull(Type::string())],
//         'inStock' => ['type' => Type::nonNull(Type::boolean())],
//         // 'price' => ['type' => Type::float()],
//         'description' => ['type' => Type::string()],
//         'category_id' => ['type' => Type::string()],
//         'brand' => ['type' => Type::string()],
//         '__typename' => ['type' => Type::string()],
//         'price' => [
//             'type' => Type::listOf($priceType),  // List of prices for different currencies
//             'resolve' => function ($product, $args, $context) use ($conn) {
//                 // Query prices table to fetch all prices for this product
//                 $query = "SELECT * FROM prices WHERE product_id = ?";
//                 $stmt = $conn->prepare($query);
//                 $stmt->bind_param("s", $product['id']);
//                 $stmt->execute();
//                 $result = $stmt->get_result();

//                 $prices = [];
//                 while ($price = $result->fetch_assoc()) {
//                     $prices[] = [
//                         'product_id' => $price['product_id'],  // Include product_id in the result
//                         'amount' => $price['amount'],
//                         'currency_label' => $price['currency_label'],
//                         'currency_symbol' => $price['currency_symbol'],
//                         '__typename' => $price['__typename'],
//                     ];
//                 }

//                 return $prices;  // Return the list of prices
//             }
//         ],
//         'gallery' => [
//             'type' => Type::ListOf($galleryType),
//             'resolve' => function ($product, $args, $context) use ($conn) {
//                 $query = 'SELECT * FROM product_gallery WHERE product_id = ?';
//                 $stmt = $conn->prepare($query);
//                 $stmt->bind_param('s', $product['id']);
//                 $stmt->execute();
//                 $result = $stmt->get_result();

//                 $gallery = [];

//                 while ($row = $result->fetch_assoc()) {
//                     $gallery[] = [
//                         'product_id' => $row['product_id'],
//                         'image_url' => $row['image_url'],
//                     ];
//                 }


//                 return $gallery;
//             }
//         ],
//         'attributes' => [
//             'type' => Type::listOf($attributeType),
//             'resolve' => function ($product, $args, $context) use ($conn) {
//                 // Query the attributes table to get the attributes for this product
//                 $query = "SELECT * FROM attributes WHERE product_id = ?";
//                 $stmt = $conn->prepare($query);
//                 $stmt->bind_param("s", $product['id']);
//                 $stmt->execute();
//                 $result = $stmt->get_result();

//                 $attributes = [];
//                 while ($attribute = $result->fetch_assoc()) {
//                     $attributes[] = $attribute;
//                 }
//                 return $attributes;
//             }
//         ]
//     ],
// ]);

// $categoryType = new ObjectType([
//     'name' => 'categories',
//     'fields' => [
//         'id' => ['type' => Type::nonNull(Type::string())],
//         'name' => ['type' => Type::nonNull(Type::string())],
//     ]
// ]);






$categoryType = require __DIR__ ."/graphql/types/CategoryType.php";
$productType = require __DIR__ ."/graphql/types/ProductType.php";
$queryType = require __DIR__ ."/graphql/queries/QueryType.php";
$mutationType = require __DIR__ ."/graphql/mutations/MutationType.php";

// Define the Query type with the updated products field
// $queryType = new ObjectType([
//     'name' => 'Query',
//     'fields' => [
//         'echo' => [
//             'type' => Type::string(),
//             'args' => [
//                 'message' => Type::nonNull(Type::string()),
//             ],
//             'resolve' => function ($root, $args) {
//                 return 'You said: ' . $args['message'];
//             },
//         ],
//         'sum' => [
//             'type' => Type::int(),
//             'args' => [
//                 'x' => Type::nonNull(Type::int()),
//                 'y' => Type::nonNull(Type::int()),
//             ],
//             'resolve' => function ($root, $args) {
//                 return $args['x'] + $args['y'];
//             },
//         ],
//         'products' => [
//             'type' => Type::listOf($productType), // Use the custom ProductType here
//             'resolve' => function () use ($conn) {
//                 // Fetch products from the database
//                 $result = $conn->query('SELECT * FROM products');
//                 $products = [];

//                 if ($result) {
//                     while ($row = $result->fetch_assoc()) {
//                         $products[] = [
//                             'id' => $row['id'],
//                             'name' => $row['name'],
//                             // 'price' => $row['price'],
//                             'inStock' =>  (bool) $row['inStock'],
//                             'description' => $row['description'],
//                             'category_id' => $row['category_id'],
//                             'brand' => $row['brand'],
//                             '__typename' => $row['__typename'],
//                         ];
//                     }
//                     $result->free(); // Free the result set
//                 }
//                 return $products;
//             },
//         ],
//         'product' => [
//             'type' => $productType,  // Return type is a single product
//             'args' => [
//                 'id' => ['type' => Type::string()],  // Optional id argument
//                 'name' => ['type' => Type::string()] // Optional name argument
//             ],
//             'resolve' => function ($root, $args, $context) use ($conn) {
//                 $query = "";
//                 $params = [];

//                 if (!empty($args['id'])) {
//                     // Search by ID
//                     $query = "SELECT * FROM products WHERE id = ?";
//                     $params = [$args['id']];
//                 } elseif (!empty($args['name'])) {
//                     // Search by name
//                     $query = "SELECT * FROM products WHERE name = ?";
//                     $params = [$args['name']];
//                 } else {
//                     // If no id or name provided, return null
//                     return null;
//                 }

//                 // Prepare the statement
//                 $stmt = $conn->prepare($query);
//                 $stmt->bind_param("s", ...$params);
//                 $stmt->execute();
//                 $result = $stmt->get_result();
//                 $product = $result->fetch_assoc();

//                 return $product;
//             }
//         ],
//         'categories' => [
//             'type' => Type::listOf($categoryType),
//             'resolve' => function ($root, $args) use ($conn) {
//                 $result = $conn->query('SELECT * FROM categories');
//                 $categories = [];
//                 if ($result) {
//                     while ($row = $result->fetch_assoc()) {
//                         $categories[] = [
//                             'id' => $row['id'],
//                             'name' => $row['name'],
//                         ];
//                     }
//                     $result->free();
//                 }
//                 return $categories;
//             }

//         ],
//         'multi' => [
//             'type' => Type::listOf(Type::int()), // Assuming multi returns a list of integers
//             'args' => [
//                 'numbers' => Type::nonNull(Type::listOf(Type::int())),
//             ],
//             'resolve' => function ($root, $args) {
//                 return array_map(function ($n) {
//                     return $n * 2; // Example: doubling the numbers in the list
//                 }, $args['numbers']);
//             },
//         ],
//     ],
// ]);
$OrderItemInputType = new InputObjectType([
    'name' => 'OrderItemInput',
    'fields' => [
        'productId' => ['type' => Type::nonNull(Type::string())],
        'name' => ['type' => Type::nonNull(Type::string())],
        'price' => ['type' => Type::nonNull(Type::float())],
        'quantity' => ['type' => Type::nonNull(Type::int())],
        'selectedAttributes' => ['type' => Type::string()],
        'gallery' => ['type' => Type::listOf(Type::string())],
        'categoryId' => ['type' => Type::nonNull(Type::string())],
        'inStock' => ['type' => Type::nonNull(Type::boolean())]
    ]
]);

$OrderType = new ObjectType([
    'name' => 'Order',
    'fields' => [
        'orderId' => ['type' => Type::nonNull(Type::string())],
        'status' => ['type' => Type::nonNull(Type::string())],
        'orderTotal' => ['type' => Type::nonNull(Type::float())],
        'orderTime' => ['type' => Type::nonNull(Type::string())], // Add orderTime

    ]
]);

// Define the Mutation type (kept as-is for now)        // 'placeOrder' => [
//     'type' => Type::nonNull(Type::string()), 
//     'args' => [
//         'input' => Type::nonNull(Type::string()), 
//     ],
//     'resolve' => function ($root, $args) {
//         return 'Order placed with input: ' . $args['input'];
//     },
// ],



$mutationType = new ObjectType([
    'name' => 'Mutation',
    'fields' =>  [

        'createOrder' => [
            'type' => $OrderType,
            'args' => [
                'items' => ['type' => Type::listOf($OrderItemInputType)],
                'userId' => ['type' => Type::nonNull(Type::string())]
            ],
            'resolve' => function ($rootValue, $args) use ($conn) {
                // $conn connection
                if ($conn->connect_error) {
                    return [
                        'orderId' => null,
                        'status' => 'error',
                        'orderTotal' => 0,
                        'message' => $conn->connect_error
                    ];
                }
                // Calculate total amount
                $totalAmount = 0;
                foreach ($args['items'] as $item) {
                    $totalAmount += $item['price'] * $item['quantity'];
                }
                $orderId = uniqid(); // Generate unique order ID
                $status = 'pending';
                // Insert order into the database
                $stmt = $conn->prepare("INSERT INTO orders (orderId, userId, status, totalAmount) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("sssd", $orderId, $args['userId'], $status, $totalAmount);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    // Insert each item into the order_items table
                    foreach ($args['items'] as $item) {
                        $stmt = $conn->prepare(
                            "INSERT INTO order_items (orderId, productId, name, price, quantity, selectedAttributes, categoryId, inStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->bind_param(
                            "sssdisis",
                            $orderId,
                            $item['productId'],
                            $item['name'],
                            $item['price'],
                            $item['quantity'],
                            $item['selectedAttributes'],
                            $item['categoryId'],
                            $item['inStock']
                        );
                        $stmt->execute();
                    }
                    $orderTime = date("h:i:s A"); // Current time in desired format
                    return [
                        'orderId' => $orderId,
                        'status' => $status,
                        'orderTotal' => $totalAmount,
                        'orderTime' => $orderTime, // Include orderTime
                    ];
                } else {
                    return [
                        'orderId' => null,
                        'status' => 'error',
                        'orderTotal' => 0,
                    ];
                }
            }
        ]
    ],
]);








// Create the schema
return new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);
