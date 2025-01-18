<?php 

// use GraphQL\Type\Definition\Type;
// use GraphQL\Type\Definition\ObjectType;

// $priceType = new ObjectType([
//     'name' => 'Price',
//     'fields' => [
//         'product_id' => ['type' => Type::nonNull(Type::string())],  // Add product_id field
//         'amount' => ['type' => Type::float()],
//         'currency_label' => ['type' => Type::nonNull(Type::string())],
//         'currency_symbol' => ['type' => Type::string()],
//         '__typename' => ['type' => Type::string()],
//     ],
// ]);

// return $priceType;


namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'product_id' => ['type' => Type::nonNull(Type::string())],  // Add product_id field
                'amount' => ['type' => Type::float()],
                'currency_label' => ['type' => Type::nonNull(Type::string())],
                'currency_symbol' => ['type' => Type::string()],
                '__typename' => ['type' => Type::string()],
            ],
        ]);
    }
}