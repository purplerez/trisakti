<?php
class UserController {
    private $conn;
    
    public function __construct()
    {
        $db = new DatabaseConnection;
        $this->conn = $db->conn;
    } 

    // method untuk menginputkan data
    public function create($data){
        $username = $data['username'];
        $password = $data['password'];
        $nama = $data['nama'];
        $type = $data['level'];

        $stat = $this->conn->prepare("INSERT INTO tb_user (username, password, nama, level) VALUES (?,?,?,?)");
        $stat->bind_param("ssss", $username, $password, $nama, $type);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;
    }

    public function index(){
        $query = "SELECT * FROM tb_user";

        $result = $this->conn->query($query);

        if($result->num_rows > 0) return $result;
        else return false;
    }
    
    public function delete($id){

        $stat = $this->conn->prepare("DELETE FROM tb_user WHERE username = ?");
        $stat->bind_param("s", $id);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;
    }

    public function edit($id){
        $stat = $this->conn->prepare("SELECT * FROM tb_user WHERE username = ?");
        $stat->bind_param("s", $id);
        $stat->execute();
        $result = $stat->get_result();

        if($result->num_rows > 0) return $result;
        else return false;
    }

    public function update($data){
        $username = $data['username'];
        //$password = $data['password'];
        $nama = $data['nama'];
        $type = $data['type'];

        $stmt = $this->conn->prepare("UPDATE tb_user SET nama = ?, level = ? WHERE username = ?");
        $stmt->bind_param("sss",  $nama, $type, $username);

        $result = $stmt->execute();
        $stmt->close();

        if($result) return true;
        else return false; 
    }

    public function resetPasswordToDefault($username) {
        $default_password = '12345678';
        $hashed_password = md5($default_password);
    
        $stat = $this->conn->prepare("UPDATE tb_user SET password = ? WHERE username = ?");
        $stat->bind_param("ss", $hashed_password, $username);
    
        $result = $stat->execute();
        $stat->close();
    
        return $result ? true : false;
    }
}



?>