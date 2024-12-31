<?php 

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

$galleryType = new ObjectType([
    'name' => 'Gallery',
    'fields' => [
        'product_id' => ['type' => Type::nonNull(Type::string())],
        'image_url' => ['type' => Type::nonNull(Type::string())],
    ],
]);
return $galleryType;