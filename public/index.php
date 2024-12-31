<?php

require '../vendor/autoload.php'; // Load dependencies

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // Handle CORS preflight requests
}

// Load the GraphQL schema
$schema = require '../src/schema.php'; // Schema definition

// Parse input payload
$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true)); // Log input for debugging
$query = $input['query'] ?? '';
$variables = $input['variables'] ?? null; // Extract variables

try {
    // Execute GraphQL query
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (Exception $e) {
    // Handle execution errors
    $output = [
        'errors' => [
            ['message' => $e->getMessage()],
        ],
    ];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($output);
