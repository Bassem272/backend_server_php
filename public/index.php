<?php

require '../vendor/autoload.php'; // Autoload dependencies

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

// Enable CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight requests for CORS
    exit(0);
}

// Create the schema
$schema = require '../src/schema.php'; // Ensure this file returns a valid schema

// Handle the request
$input = json_decode(file_get_contents('php://input'), true);
error_log(print_r($input, true)); // Debug input
$query = $input['query'] ?? '';
$variables = $input['variables'] ?? null; // Handle query variables if needed

try {
    // Execute the query
    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray();
} catch (Exception $e) {
    // Catch and return errors in case of query execution issues
    $output = [
        'errors' => [
            ['message' => $e->getMessage()],
        ],
    ];
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($output);
