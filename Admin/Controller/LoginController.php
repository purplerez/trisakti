<?php 

class LoginController{
    private $conn; 

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

        $db = new DatabaseConnection;
        $this->conn = $db->conn;
    }

    public function login($username, $password){

        $query = "SELECT * FROM tb_user WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($query);
        $password = md5($password);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $level = $row['level'];
            
            // Update kolom last_login
            $current_time = date('Y-m-d H:i:s'); // Waktu saat ini
            $updateQuery = "UPDATE tb_user SET lastlogin = ? WHERE username = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $current_time, $username);
            $updateStmt->execute();
            
            return ['status'=>true, 'level' => $level];
        }
        else return ['status'=> false];
    }
}
