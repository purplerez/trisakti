<?php 
class TransaksiController{
    private $conn; 
    
    public function __construct()
    {
        $db = new DatabaseConnection;
        $this->conn = $db->conn;
    }
    public function index($status = 1){
      
            $query = "SELECT id, nama_tiket, tarif FROM tb_jenistiket WHERE status = '$status'";
      
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

    public function view_transaksi_by_date($tanggal) {
        $query = "SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, t.id,  
                  SUM(CASE WHEN jenis.status = '1' THEN d.total_pendapatan ELSE 0 END) - 
                  SUM(CASE WHEN jenis.status = '0' THEN d.total_pendapatan ELSE 0 END) AS total
                  FROM tb_transaksi t
                  LEFT JOIN tb_detail_trans d ON t.id = d.id_transaksi
                  LEFT JOIN tb_jenistiket jenis ON d.id_tiket = jenis.id
                  WHERE t.tanggal = ?
                  GROUP BY t.id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
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
                $harga = (int) $this->get_harga($jns);
                $jumlahProduksi = (int) $produksi[$index];
                $params[] = $jumlahProduksi;
                $params[] = $harga * $jumlahProduksi;
            }
            $detailStat->bind_param($types, ...$params);
            $result = $detailStat->execute();
            $detailStat->close();
        }
        if ($result) return true;
        else return false;
    }
    
    public function view_transaksi(){
        $query = "SELECT t.tanggal, t.jam,  t.pelabuhan,  t.trip,  t.id,  
                    SUM(CASE WHEN jenis.status = '1' THEN detail.total_pendapatan ELSE 0 END) -
                    SUM(CASE WHEN jenis.status = '0' THEN detail.total_pendapatan ELSE 0 END) AS total
                FROM 
                    tb_transaksi t
                LEFT JOIN 
                    tb_detail_trans detail ON t.id = detail.id_transaksi
                LEFT JOIN 
                    tb_jenistiket jenis ON detail.id_tiket = jenis.id
                GROUP BY 
                    t.id, t.tanggal, t.jam, t.pelabuhan, t.trip
                ";


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

    public function edit($id) {
        $query = "SELECT * FROM tb_transaksi WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Mengembalikan data dalam bentuk array
        } else {
            return false;
        }
    }
    
    public function update($data) {
        $id = $data['id'];
        $tanggal = $data['tgl'];
        $jam = $data['waktu'];
        $trip = $data['trip'];
        $pelabuhan = $data['pelabuhan'];
        $produksi = $data['produksi'];
        $idjenis = $data['idjenis'];
    
        // Update query untuk tb_transaksi
        $stmt = $this->conn->prepare("UPDATE tb_transaksi SET tanggal = ?, jam = ?, trip = ?, pelabuhan = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $tanggal, $jam, $trip, $pelabuhan, $id);
        
        // Eksekusi update
        $result = $stmt->execute();
        $stmt->close();
    
        if ($result) {
            // Hapus detail transaksi lama dan tambahkan yang baru
            $this->delete_detail($id);
            return $this->create_detail($id, $idjenis, $produksi);
        } else {
            return false;
        }
    }   
    
    public function getDetailTransaksi($id_transaksi) {
        $query = "SELECT * FROM tb_detail_trans WHERE id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $details = [];
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
    
        $stmt->close();
        return $details;
    }
    
    private function delete_detail($id_transaksi) {
        $stmt = $this->conn->prepare("DELETE FROM tb_detail_trans WHERE id_transaksi = ?");
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        $stmt->close();
    }

    public function validasi($data){
        foreach($data as $rec => $value)
        {
            $nilai = trim($value);
            if((empty($nilai)) || $nilai == '' || $nilai = 0)
                return $rec;
        }

        return 0;
    }
    public function validasiDetail($data1, $data2){
        if(count($data1) != count($data2))
            return false;
        else 
            return 0;
    }
    
}
?>
