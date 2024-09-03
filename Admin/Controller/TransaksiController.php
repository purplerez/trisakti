<?php 
class TransaksiController{
    private $conn; 
    
    public function __construct()
    {
        $db = new DatabaseConnection;
        $this->conn = $db->conn;
    }
    public function index( ){
        $query = "SELECT * from tb_jenistiket";

        $result = $this->conn->query($query);

        if($result->num_rows > 0) return $result;
        else return false;
    }
    private function get_harga($idjenis){
        $query = "SELECT tarif from tb_jenistiket where id = $idjenis";

        $result = $this->conn->query($query);
         
        if($result->num_rows > 0) 
        {
            $rec = $result->fetch_assoc();
            return $rec['tarif'];
        }
        else return false;
    }

    public function create($data, $jenis, $produksi){
        $tanggal = $data['tanggal'];
        $jam = $data['waktu'];
        $trip = $data['trip'];
        $pelabuhan = $data['pelabuhan'];
        $username = 'rez';
        
        $stat = $this->conn->prepare("INSERT INTO tb_transaksi (tanggal, jam, trip, pelabuhan, username) VALUES (?,?,?,?,?)");
        $stat->bind_param("sssss", $tanggal, $jam, $trip, $pelabuhan, $username);

        $result = $stat->execute();

        $stat->close();

        if($result){
            $last_id = $this->conn->insert_id;

            $detail = $this->create_detail($last_id, $jenis, $produksi);
            if($detail)return true;
            else return false;
        }
        else return false;
        // $id = $this->get_id("tanggal", $tanggal);    
    }

    private function create_detail($id, $jenis, $produksi){
        if (count($jenis) == count($produksi)) {
            $values = [];
            for ($i = 0; $i < count($jenis); $i++) {
                $values[] = "($id, ?, ?, ?)";
            }
            
            $query = "INSERT INTO tb_detail_trans (id_transaksi, id_tiket, produksi, total_pendapatan) VALUES " . implode(", ", $values);
            $detailStat = $this->conn->prepare($query);

            $types = str_repeat("ssi", count($jenis));
            $params = [];
            foreach ($jenis as $index => $jns) {
                $params[] = $jns;
                $harga = $this->get_harga($jns);
                $params[] = $produksi[$index];
                $params[] = $harga*$produksi[$index];
            }
            $detailStat->bind_param($types, ...$params);
            $result = $detailStat->execute();
            $detailStat->close();
        }
        if ($result) return true;
        else return false;
    }
    
    public function view_transaksi(){
        $query = "SELECT t.tanggal,t.jam, t.pelabuhan, t.trip , t.id, SUM(d.total_pendapatan) AS total_pendapatan FROM tb_transaksi t
                        LEFT JOIN tb_detail_trans d ON t.id = d.id_transaksi
                        GROUP BY t.id";


        $result = $this->conn->query($query);

        if($result->num_rows > 0) return $result;
        else return false;
    }

    public function delete($id){
        $stat = $this->conn->prepare("DELETE FROM tb_transaksi WHERE id = ?");
        $stat->bind_param("i", $id);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;

    } 

    public function edit($id){
        $query = "SELECT * FROM tb_transaksi WHERE id = $id";
        $result = $this->conn->query($query);

        // $query->close();

        if($result->num_rows>0) return $result;
        else return false;
    }

    public function update($data){
        $id = $data['id'];
        $produksi = $data['produksi'];

        $stmt = $this->conn->prepare("UPDATE tb_transaksi SET produksi = ? WHERE id = ?");
        $stmt->bind_param("ii", $produksi, $id);

        $result = $stmt->execute();
        $stmt->close();

        if($result) return true;
        else return false; 
    }
    
}
?>