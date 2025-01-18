<?php 

// use GraphQL\Type\Definition\Type;
// use GraphQL\Type\Definition\ObjectType;

// $orderType = new ObjectType([
//     'name' => 'Order',
//     'fields' => [
//         'orderId' => ['type' => Type::nonNull(Type::string())],
//         'status' => ['type' => Type::nonNull(Type::string())],
//         'orderTotal' => ['type' => Type::nonNull(Type::float())],
//         'orderTime' => ['type' => Type::nonNull(Type::string())], // Add orderTime

//     ]
// ]);



namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderType
{
    private $type;

    public function __construct()
    {
        $this->type = new ObjectType([
            'name' => 'Order',
            'fields' => [
                'orderId' => ['type' => Type::nonNull(Type::string())],
                'status' => ['type' => Type::nonNull(Type::string())],
                'orderTotal' => ['type' => Type::nonNull(Type::float())],
                'orderTime' => ['type' => Type::nonNull(Type::string())], // Add orderTime
            ],
        ]);
    }

    public function getType()
    {
        return $this->type;
    }
}
