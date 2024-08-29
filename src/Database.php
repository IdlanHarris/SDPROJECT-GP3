<?php
namespace App;

use Dotenv\Dotenv;
use PDO;

class Database {

    private $pdo;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
    }

    public function connect() {
        if (!$this->pdo) {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];
            $port = $_ENV['DB_PORT'];
            $databaseConnectionUri = "pgsql:host=$host;dbname=$dbname;port=$port";
            $this->pdo = new PDO($databaseConnectionUri, $username, $password);
        }
        return $this->pdo;
    }

    public function disconnect() {
        if ($this->pdo) {
            $this->pdo = null;
        }
    }
}