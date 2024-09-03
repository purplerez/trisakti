<?php 

class LoginController{
    private $conn; 

    public function __construct()
    {
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
            return ['status'=>true, 'level' =>$level];
        }
        else return ['status'=> false];
    }
}
?>