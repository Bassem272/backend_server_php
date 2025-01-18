<?php
// namespace App\GraphQL\Mutations;

// use GraphQL\Type\Definition\ObjectType;
// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\OrderType;
// use App\GraphQL\Types\OrderItemInputType;

// $conn = require __DIR__.'/../../db.php';


// $mutationType = new ObjectType([
//     'name' => 'Mutation',
//     'fields' =>  [
//         'createOrder' => [
//             'type' => $orderType,
//             'args' => [
//                 'items' => ['type' => Type::listOf($orderItemInputType)],
//                 'userId' => ['type' => Type::nonNull(Type::string())]
//             ],
//             'resolve' => function ($rootValue, $args) use ($conn) {
//                 // $conn connection
//                 if ($conn->connect_error) {
//                     return [
//                         'orderId' => null,
//                         'status' => 'error',
//                         'orderTotal' => 0,
//                         'message' => $conn->connect_error
//                     ];
//                 }
//                 // Calculate total amount
//                 $totalAmount = 0;
//                 foreach ($args['items'] as $item) {
//                     $totalAmount += $item['price'] * $item['quantity'];
//                 }
//                 $orderId = uniqid(); // Generate unique order ID
//                 $status = 'pending';
//                 // Insert order into the database
//                 $stmt = $conn->prepare("INSERT INTO orders (orderId, userId, status, totalAmount) VALUES (?, ?, ?, ?)");
//                 $stmt->bind_param("sssd", $orderId, $args['userId'], $status, $totalAmount);
//                 $stmt->execute();
//                 if ($stmt->affected_rows > 0) {
//                     // Insert each item into the order_items table
//                     foreach ($args['items'] as $item) {
//                         $stmt = $conn->prepare(
//                             "INSERT INTO order_items (orderId, productId, name, price, quantity, selectedAttributes, categoryId, inStock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
//                         );
//                         $stmt->bind_param(
//                             "sssdisis",
//                             $orderId,
//                             $item['productId'],
//                             $item['name'],
//                             $item['price'],
//                             $item['quantity'],
//                             $item['selectedAttributes'],
//                             $item['categoryId'],
//                             $item['inStock']
//                         );
//                         $stmt->execute();
//                     }
//                     $orderTime = date("h:i:s A"); // Current time in desired format
//                     return [
//                         'orderId' => $orderId,
//                         'status' => $status,
//                         'orderTotal' => $totalAmount,
//                         'orderTime' => $orderTime, // Include orderTime
//                     ];
//                 } else {
//                     return [
//                         'orderId' => null,
//                         'status' => 'error',
//                         'orderTotal' => 0,
//                     ];
//                 }
//             }
//         ]
//     ],
// ]);

// return $mutationType;


// namespace App\GraphQL;

// use GraphQL\Type\Definition\ObjectType;
// use App\GraphQL\Mutations\CreateOrderMutation;

// class MutationType extends ObjectType
// {
//     public function __construct()
//     {
//         parent::__construct([
//             'name' => 'Mutation',
//             'fields' => [
//                 'createOrder' => (new CreateOrderMutation())->getFieldDefinition(),
//             ],
//         ]);
//     }
// }


namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
class MutationType
{
    private $type;

    public function __construct($conn, $orderType, $orderItemInputType)
    {
        $this->type = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => $orderType,
                    'args' => [
                        'items' => Type::nonNull(Type::listOf($orderItemInputType)),
                        'userId' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => function ($rootValue, $args) use ($conn) {
                        // Delegate to CreateOrderMutation
                        //var_dump($args);
                        // Example for logging errors if GraphQL is in production
                        // error_log('Error during order creation: ' . json_encode($args));

                        // // Or more specific logging per mutation resolution:
                        // error_log('OrderItems data: ' . json_encode($args['items']));
                        $mutation = new CreateOrderMutation($conn);
                        return $mutation->handle($rootValue, $args);
                    },
                ],
            ],
        ]);
    }

    public function getType()
    {
        return $this->type;
    }
}
