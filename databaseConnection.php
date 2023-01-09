<?php

class Connectiontodatabase
{
    //database connection to localhost

    private $server = "localhost";
    private $username = "user";
    private $password = "";
    private $database = "rtcamp";
    public $conn;
    public function __construct()
    {
        $this->conn = new MySQLi($this->server, $this->username, $this->password, $this->database);
    }
}

        $connect = new Connectiontodatabase();
        $mysqli = $connect->conn;

?>
