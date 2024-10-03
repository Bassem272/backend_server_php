<?php
// Enable CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/vendor/autoload.php';
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
echo "here we go; Hello Bassem is here and just started ok lol 
Thanks and praise are to Allah the mighty who gave us his blessings. ";
echo "</br>";

require "./src/db.php";
$schema = require "./src/schema.php";

echo "</br>";

// var_dump($conn);

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
?>