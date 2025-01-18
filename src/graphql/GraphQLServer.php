<?php

namespace App\GraphQL;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

class GraphQLServer
{
    private Schema $schema;

    public function __construct()
    {
        // Load the schema file dynamically
        $this->schema = require __DIR__ . '/../schema.php';
    }

    public function handleRequest(string $input): array
    {
        // Decode the incoming GraphQL query
        $payload = json_decode($input, true);
        $query = $payload['query'] ?? '';
        $variables = $payload['variables'] ?? null;

        // Execute the query and return the result
        return $this->executeQuery($query, $variables);
    }

    private function executeQuery(string $query, ?array $variables): array
    {
        try {
            // Execute the GraphQL query using the loaded schema
            $result = GraphQL::executeQuery($this->schema, $query, null, null, $variables);
            return $result->toArray();
        } catch (\Exception $e) {
            // Return a standardized error response
            return [
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ];
        }
    }
}
