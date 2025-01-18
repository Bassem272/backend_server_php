<?php

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\productType;
// use App\Database\Connection;

// $connection = new Connection();
// $conn = $connection->getConnection();

// $productQuery = [
//     'product' => [
//         'type' => Type::listOf($productType), // Return a list of products
//         'args' => [
//             'id' => ['type' => Type::string()],
//             'name' => ['type' => Type::string()],
//         ],
//         'resolve' => function ($root, $args, $context) use ($conn) {
//             $query = "SELECT * FROM products";
//             $params = [];
            
//             if (!empty($args['id'])) {
//                 $query .= " WHERE id = ?";
//                 $params[] = $args['id'];
//             } elseif (!empty($args['name'])) {
//                 $query .= " WHERE name = ?";
//                 $params[] = $args['name'];
//             } else {
//                 // Return an empty list when no filter is applied
//                 return [];
//             }

//             // Prepare and execute the statement
//             $stmt = $conn->prepare($query);
//             if (!$stmt) {
//                 throw new \Exception('Failed to prepare the SQL query: ' . $conn->error);
//             }

//             $stmt->bind_param(str_repeat("s", count($params)), ...$params);
//             $stmt->execute();
//             $result = $stmt->get_result();

//             // Fetch results as an array of products
//             $products = [];
//             while ($row = $result->fetch_assoc()) {
//                 $products[] = $row;
//             }

//             return $products; // Return an array of products
//         },
//     ],
// ];

// return $productQuery;



// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\ProductType;
// use App\Database\Connection;

// class ProductQuery
// {
//     private $conn;

//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//     }

//     public function getProductQuery()
//     {
//         return [
//             'product' => [
//                 'type' => Type::listOf($productQuery),
//                 'args' => [
//                     'id' => ['type' => Type::string()],
//                     'name' => ['type' => Type::string()],
//                 ],
//                 'resolve' => function ($root, $args, $context) {
//                     return $this->resolveProductQuery($args);
//                 },
//             ],
//         ];
//     }

//     private function resolveProductQuery($args)
//     {
//         $query = "SELECT * FROM products";
//         $params = [];
        
//         if (!empty($args['id'])) {
//             $query .= " WHERE id = ?";
//             $params[] = $args['id'];
//         } elseif (!empty($args['name'])) {
//             $query .= " WHERE name = ?";
//             $params[] = $args['name'];
//         } else {
//             return [];
//         }

//         $stmt = $this->conn->prepare($query);
//         if (!$stmt) {
//             throw new \Exception('Failed to prepare SQL query: ' . $this->conn->error);
//         }

//         $stmt->bind_param(str_repeat("s", count($params)), ...$params);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         $products = [];
//         while ($row = $result->fetch_assoc()) {
//             $products[] = $row;
//         }

//         return $products;
//     }
// }


// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;
// use App\GraphQL\Types\ProductType;
// use App\Database\Connection;

// class ProductQuery
// {
//     private $conn;
//     private $productType;

//     public function __construct($conn)
//     {
//         $this->conn = $conn;
//         $this->productType = new ProductType(); // Instantiate ProductType
//     }

//     // Method to get the product query
//     public function getProductQuery()
//     {
//         return [
//             'product' => [
//                 'type' => Type::listOf($this->productType), // Use instantiated productType here
//                 'args' => [
//                     'id' => ['type' => Type::string()],
//                     'name' => ['type' => Type::string()],
//                 ],
//                 'resolve' => function ($root, $args, $context) {
//                     return $this->resolveProductQuery($args); // Use the method to resolve the query
//                 },
//             ],
//         ];
//     }

//     // Resolving the query for products from the database
//     private function resolveProductQuery($args)
//     {
//         $query = "SELECT * FROM products";
//         $params = [];

//         if (!empty($args['id'])) {
//             $query .= " WHERE id = ?";
//             $params[] = $args['id'];
//         } elseif (!empty($args['name'])) {
//             $query .= " WHERE name = ?";
//             $params[] = $args['name'];
//         } else {
//             return []; // If no arguments provided, return an empty array
//         }

//         // Prepare and execute the query
//         $stmt = $this->conn->prepare($query);
//         if (!$stmt) {
//             throw new \Exception('Failed to prepare SQL query: ' . $this->conn->error);
//         }

//         $stmt->bind_param(str_repeat("s", count($params)), ...$params);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         $products = [];
//         while ($row = $result->fetch_assoc()) {
//             $products[] = $row; // Collect each row as a product
//         }

//         return $products;
//     }
// }



// namespace App\GraphQL\Queries;

// use GraphQL\Type\Definition\Type;

// class ProductQuery
// {
//     private $conn;
//     private $productType;

//     public function __construct($conn, $productType, $priceType, $galleryType, $attributeType)
//     {
//         $this->conn = $conn;
//         $this->productType = $productType;

//         // Optionally store priceType, galleryType, and attributeType for extended functionality.
//     }

//     public function getProductType()
//     {
//         return $this->productType;
//     }
    

//     public function resolveProductQuery($args)
//     {
//         $query = "SELECT * FROM products";
//         $params = [];

//         if (!empty($args['id'])) {
//             $query .= " WHERE id = ?";
//             $params[] = $args['id'];
//         } elseif (!empty($args['name'])) {
//             $query .= " WHERE name = ?";
//             $params[] = $args['name'];
//         } else {
//             return []; // Return an empty list when no filter is applied.
//         }

//         $stmt = $this->conn->prepare($query);
//         if (!$stmt) {
//             throw new \Exception('Failed to prepare SQL query: ' . $this->conn->error);
//         }

//         $stmt->bind_param(str_repeat("s", count($params)), ...$params);
//         $stmt->execute();
//         $result = $stmt->get_result();

//         $products = [];
//         while ($row = $result->fetch_assoc()) {
//             $products[] = $row;
//         }

//         return $products;
//     }
// }
namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;

class ProductQuery
{
    private $conn;
    private $productType;

    public function __construct($conn, $productType)
    {
        $this->conn = $conn;
        $this->productType = $productType;
    }

    /**
     * Returns the GraphQL field definition for products query.
     */
    public function toGraphQL()
    {
        return [
            'product' => [
                'type' => $this->productType,
                'args' => [
                    'id' => Type::string(),
                    // 'name' => Type::string(),
                ],
                'resolve' => function ($root, $args, $context) {
                    return $this->resolveProductQuery($args);
                },
            ],
        ];
    }

    /**
     * Resolves the product query based on input arguments.
     */
    private function resolveProductQuery($args)
    {
        $query = "SELECT * FROM products";
        $params = [];
        $filters = [];

        if (!empty($args['id'])) {
            $filters[] = "id = ?";
            $params[] = $args['id'];
        }

        if (!empty($args['name'])) {
            $filters[] = "name = ?";
            $params[] = $args['name'];
        }

        if (!empty($filters)) {
            $query .= " WHERE " . implode(" AND ", $filters);
        }

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new \Exception('Failed to prepare SQL query: ' . $this->conn->error);
        }

        if ($params) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Return a single product or null if not found.
    }
}
