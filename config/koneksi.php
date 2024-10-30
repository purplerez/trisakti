<?php

class DatabaseConnection {
    protected $server = 'localhost';
    // protected $user = 'root_rez';
    // protected $pass = 'ires123';
    // protected $db = 'trisakti';
    protected $user = 'root';
    protected $pass = '';
    
    protected $db = 'trisakti';
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
