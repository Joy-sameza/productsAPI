<?php
class Database
{
    private $hostName = 'localhost';
    private $userName = 'root';
    private $pass = '12345';
    private $dbName = 'mcs_db';
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->hostName . ';dbname=' . $this->dbName, $this->userName, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connectio Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
