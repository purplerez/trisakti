<?php

class DatabaseConnection {
    protected $server = 'localhost';
    protected $user = 'root_rez';
    protected $pass = 'ires123';
    protected $db = 'trimas_db';

    public $conn;

    public function __construct() {
        $conn = new mysqli($this->server, $this->user, $this->pass, $this->db);

        if ($conn->connect_error) {
            echo "Connection Failed: " . $conn->connect_error;
        }

        return $this->conn = $conn;
    }
}
?>
