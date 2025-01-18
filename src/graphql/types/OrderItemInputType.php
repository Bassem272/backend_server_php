<?php 



// use GraphQL\Type\Definition\Type;
// use GraphQL\Type\Definition\InputObjectType;


// $orderItemInputType = new InputObjectType([
//     'name' => 'OrderItemInput',
//     'fields' => [
//         'productId' => ['type' => Type::nonNull(Type::string())],
//         'name' => ['type' => Type::nonNull(Type::string())],
//         'price' => ['type' => Type::nonNull(Type::float())],
//         'quantity' => ['type' => Type::nonNull(Type::int())],
//         'selectedAttributes' => ['type' => Type::string()],
//         'gallery' => ['type' => Type::listOf(Type::string())],
//         'categoryId' => ['type' => Type::nonNull(Type::string())],
//         'inStock' => ['type' => Type::nonNull(Type::boolean())]
//     ]
// ]);


// return $orderItemInputType;


namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\InputObjectType;

class OrderItemInputType
{
    private $type;

    public function __construct()
    {
        $this->type = new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => ['type' => Type::nonNull(Type::string())],
                'name' => ['type' => Type::nonNull(Type::string())],
                'price' => ['type' => Type::nonNull(Type::float())],
                'quantity' => ['type' => Type::nonNull(Type::int())],
                'selectedAttributes' => ['type' => Type::string()],
                'gallery' => ['type' => Type::listOf(Type::string())],
                'categoryId' => ['type' => Type::nonNull(Type::string())],
                'inStock' => ['type' => Type::nonNull(Type::boolean())],
            ],
        ]);
    }

    public function getType()
    {
        return $this->type;
    }
}
