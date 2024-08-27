<?php 

class JenistiketController {

    public function __construct()
    {
        $db = new DatabaseConnection;
        $this->conn = $db->conn;
    } 

    // method untuk menginputkan data
    public function create($data){
        $nama_tiket = $data['nama_tiket'];
        $tarif = $data['tarif'];
        $status = $data['status'];

        $stat = $this->conn->prepare("INSERT INTO tb_jenistiket (nama_tiket, tarif, status) VALUES (?,?,?)");
        // echo "INSERT INTO tb_jenistiket (nama_tiket, tarif) VALUES ('$nama_tiket',$tarif)";
        $stat->bind_param("sii", $nama_tiket, $tarif, $status);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;
    }

    public function index(){
        $query = "SELECT * FROM tb_jenistiket";

        $result = $this->conn->query($query);

        if($result->num_rows > 0) return $result;
        else return false;
    }

    public function delete($id){
        $stat = $this->conn->prepare("DELETE FROM tb_jenistiket WHERE id = ?");
        $stat->bind_param("i", $id);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;

    }

    public function edit($id){
        $query = "SELECT * FROM tb_jenistiket WHERE id = $id";
        $result = $this->conn->query($query);

        // $query->close();

        if($result->num_rows>0) return $result;
        else return false;
    }

    public function update($data){
        $id = $data['id'];
        $nama_tiket = $data['nama_tiket'];
        $tarif = $data['tarif'];

        $stmt = $this->conn->prepare("UPDATE tb_jenistiket SET nama_tiket = ?, tarif = ? WHERE id = ?");
        $stmt->bind_param("sii", $nama_tiket, $tarif, $id);

        $result = $stmt->execute();
        $stmt->close();

        if($result) return true;
        else return false; 
    }



}

?>