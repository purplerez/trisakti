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
        // $query = "SELECT t.tanggal,t.jam, t.pelabuhan, t.trip , t.id, 
        //                 (SELECT sum(detail.total_pendapatan) AS total_pengeluaran FROM tb_detail_trans detail 
		// 									LEFT JOIN tb_jenistiket jenis ON detail.id_tiket = jenis.id
		// 									WHERE detail.id_transaksi = t.id AND jenis.`status` = '0'  ) AS total_pengeluaran, 
        //                 (SELECT sum(detail.total_pendapatan) AS total_pengeluaran FROM tb_detail_trans detail 
		// 									LEFT JOIN tb_jenistiket jenis ON detail.id_tiket = jenis.id
		// 									WHERE detail.id_transaksi = t.id AND jenis.`status` = '1'  ) AS total_pendapatan
        //                 FROM tb_transaksi t
        //                 LEFT JOIN tb_detail_trans d ON t.id = d.id_transaksi
        //                 GROUP BY t.id";

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

    public function edit($id){
        // $query = "SELECT * FROM tb_transaksi WHERE id = $id";
        // $result = $this->conn->query($query);

        $stat = $this->conn->prepare("SELECT * FROM tb_transaksi WHERE id = ?");
        $stat->bind_param("i", $id);

        $result = $stat->execute();
        $stat->close();

        if($result) return true;
        else return false;
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