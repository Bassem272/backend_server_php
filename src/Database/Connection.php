<?php

namespace App\Database;

class Connection
{
    private $conn;

    public function __construct()
    {
        $hostname = "localhost";
        $username = "root";
        $userpassword = "1234";
        $dbname = "store1";

        // Create connection
        $this->conn = new \mysqli($hostname, $username, $userpassword, $dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
