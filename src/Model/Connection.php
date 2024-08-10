<?php

namespace App\Model;

use PDO;
use PDOException;

class Connection
{
    private PDO $connection;
    private string $user = DB_USER;
    private string $host = DB_HOST;
    private string $password = DB_PASSWORD;
    private string $database = DB_NAME;

    public function __construct()
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=utf8',
                $this->user,
                $this->password
            );

            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection error:: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}