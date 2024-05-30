<?php

namespace App\Config;

use PDO;

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;

    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $exception) {
            throw new \PDOException("Error de conexión: " . $exception->getMessage());
        }
    }
}

?>
