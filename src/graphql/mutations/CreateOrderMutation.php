<?php
// src/GraphQL/Types/OrderType.php
// namespace App\GraphQL\Mutations;

// use GraphQL\Type\Definition\Type;
// $conn = require __DIR__.'/../../db.php';
// $orderItemInputType = require __DIR__.'/../types/OrderItemInputType.php';
// $orderType = require __DIR__.'/../types/OrderType.php';



// $createOrder = [
//     'createOrder' => [ 
//     [
//         'type' => $orderType,
//         'args' => [
//             'items' => ['type' => Type::listOf($orderItemInputType)],
//             'userId' => ['type' => Type::nonNull(Type::string())]
//         ],
//         'resolve' => function ($rootValue, $args) use ($conn) {
//             // $conn connection
//             if ($conn->connect_error) {
//                 return [
//                     'orderId' => null,
//                     'status' => 'error',
//                     'orderTotal' => 0,
//                     'message' => $conn->connect_error
//                 ];
//             }

//             // Calculate total amount
//             $totalAmount = 0;
//             foreach ($args['items'] as $item) {
//                 $totalAmount += $item['price'] * $item['quantity'];
//             }

//             $orderId = uniqid(); // Generate unique order ID
//             $status = 'pending';

//             // Insert order into the database
//             $stmt = $conn->prepare("INSERT INTO orders (orderId, userId, status, totalAmount) VALUES (?, ?, ?, ?)");
//             $stmt->bind_param("sssd", $orderId, $args['userId'], $status, $totalAmount);
//             $stmt->execute();

//             if ($stmt->affected_rows > 0) {
//                 // Insert each item into the order_items table
//                 foreach ($args['items'] as $item) {
//                     $stmt = $conn->prepare(
//                         "INSERT INTO order_items (orderId, productId, name, price, quantity, selectedAttributes, categoryId, inStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
//                     );
//                     $stmt->bind_param(
//                         "sssdisis",
//                         $orderId,
//                         $item['productId'],
//                         $item['name'],
//                         $item['price'],
//                         $item['quantity'],
//                         $item['selectedAttributes'],
//                         $item['categoryId'],
//                         $item['inStock']
//                     );
//                     $stmt->execute();
//                 }

//                 $orderTime = date("h:i:s A"); // Current time in desired format
//                 return [
//                     'orderId' => $orderId,
//                     'status' => $status,
//                     'orderTotal' => $totalAmount,
//                     'orderTime' => $orderTime, // Include orderTime
//                 ];
//             } else {
//                 return [
//                     'orderId' => null,
//                     'status' => 'error',
//                     'orderTotal' => 0,
//                 ];
//             }
//         }
//     ]
//     ]
// ];

// return $createOrder;


// namespace App\GraphQL\Mutations;

// class CreateOrderMutation
// {
//     private $conn;

//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//     }

//     public function handle($rootValue, $args)
//     {
//         if ($this->conn->connect_error) {
//             return [
//                 'orderId' => null,
//                 'status' => 'error',
//                 'orderTotal' => 0,
//                 'orderTime' => null,
//                 'message' => $this->conn->connect_error,
//             ];
//         }

//         // Calculate total amount
//         $totalAmount = 0;
//         foreach ($args['items'] as $item) {
//             $totalAmount += $item['price'] * $item['quantity'];
//         }

//         $orderId = uniqid(); // Generate unique order ID
//         $status = 'pending';

//         // Insert order into database
//         $stmt = $this->conn->prepare("INSERT INTO orders (orderId, userId, status, totalAmount) VALUES (?, ?, ?, ?)");
//         $stmt->bind_param("sssd", $orderId, $args['userId'], $status, $totalAmount);
//         $stmt->execute();

//         if ($stmt->affected_rows > 0) {
//             // Insert each item into the order_items table
//             foreach ($args['items'] as $item) {
//                 $stmt = $this->conn->prepare(
//                     "INSERT INTO order_items (orderId, productId, name, price, quantity, selectedAttributes, categoryId, inStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
//                 );
//                 $stmt->bind_param(
//                     "sssdisis",
//                     $orderId,
//                     $item['productId'],
//                     $item['name'],
//                     $item['price'],
//                     $item['quantity'],
//                     $item['selectedAttributes'],
//                     $item['categoryId'],
//                     $item['inStock']
//                 );
//                 $stmt->execute();
//             }

//             $orderTime = date("h:i:s A"); // Current time in desired format
//             return [
//                 'orderId' => $orderId,
//                 'status' => $status,
//                 'orderTotal' => $totalAmount,
//                 'orderTime' => $orderTime,
//             ];
//         } else {
//             return [
//                 'orderId' => null,
//                 'status' => 'error',
//                 'orderTotal' => 0,
//                 'orderTime' => null,
//             ];
//         }
//     }
// }
namespace App\GraphQL\Mutations;

class CreateOrderMutation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function handle($rootValue, $args)
    {
        if ($this->conn->connect_error) {
            return [
                'orderId' => null,
                'status' => 'error',
                'orderTotal' => 0,
                'orderTime' => null,
                'message' => $this->conn->connect_error,
            ];
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($args['items'] as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $orderId = uniqid(); // Generate unique order ID
        $status = 'pending';

        // Insert order into database
        $stmt = $this->conn->prepare("INSERT INTO orders (orderId, userId, status, totalAmount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $orderId, $args['userId'], $status, $totalAmount);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Insert each item into the order_items table
            foreach ($args['items'] as $item) {
                // Check if selectedAttributes is a string (likely JSON data) and decode it if necessary
                if (isset($item['selectedAttributes']) && is_string($item['selectedAttributes'])) {
                    $item['selectedAttributes'] = json_decode($item['selectedAttributes'], true); // Decode JSON to an array
                }

                // Insert order item into the database
                $stmt = $this->conn->prepare(
                    "INSERT INTO order_items (orderId, productId, name, price, quantity, selectedAttributes, categoryId, inStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param(
                    "sssdisis",
                    $orderId,
                    $item['productId'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    json_encode($item['selectedAttributes']), // Make sure to store as JSON string if needed
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
                'orderTime' => $orderTime,
            ];
        } else {
            return [
                'orderId' => null,
                'status' => 'error',
                'orderTotal' => 0,
                'orderTime' => null,
            ];
        }
    }
}
