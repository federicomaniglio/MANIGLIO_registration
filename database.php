<?php

// SINGLETON
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=maniglio_registrazione", "root","");

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }


}