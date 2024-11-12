<?php
ob_start();
require ('../user/config.php');
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$imgData = base64_encode(file_get_contents('img/logo.png'));

$html = '
<img src="data:image/png;base64,' . $imgData . '" style="float: left; width: 100px">

<div style="text-align: center; margin-top: 20px; margin-bottom: 30px">
    <div style="font-size: 18px">LAPORAN PRODUKSI KAPAL</div>
    <div style="font-size: 18px">KMP. TRIMAS LAILA</div>
</div>

<hr style="border: 0.5px solid black; margin: 10px 5px 10px 5px">';

$id_transaksi = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id_transaksi === null) {
    die('Error: ID transaksi tidak ditemukan.');
}

$sql_trans = "SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, j.nama_tiket, j.tarif, d.produksi 
              FROM tb_transaksi AS t 
              JOIN tb_detail_trans AS d ON t.id = d.id_transaksi 
              JOIN tb_jenistiket AS j ON d.id_tiket = j.id
              WHERE t.id = ? AND j.status = '1' ";

$sql_trans_pengeluaran = "SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, j.nama_tiket, j.tarif, d.produksi 
                FROM tb_transaksi AS t 
                JOIN tb_detail_trans AS d ON t.id = d.id_transaksi 
                JOIN tb_jenistiket AS j ON d.id_tiket = j.id
                WHERE t.id = ? AND j.status = '0' ";

// Prepare statement
$stmt = mysqli_prepare($koneksi, $sql_trans);
$stmt_trans = mysqli_prepare($koneksi, $sql_trans_pengeluaran);


if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
    mysqli_stmt_execute($stmt);
    $result_transaksi = mysqli_stmt_get_result($stmt);
    
    if ($result_transaksi) {
        $subtotal = 0;
        $no = 1;
        
        $data_header = mysqli_fetch_array($result_transaksi);  // Fetch the first row to display header details
        
        $html .= '
        <table width="100%" style="font-size: 17px; margin-top: 40px;">
            <tr>
                <td style="width: 50%;">
                    <table>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>' . date('d-m-Y', strtotime($data_header['tanggal'])) . '</td>
                        </tr>
                        <tr>
                            <td>Trip</td>
                            <td>:</td>
                            <td>' . $data_header['trip'] . '</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; text-align: right;">
                    <table style="float: right;">
                        <tr>
                            <td>Pelabuhan</td>
                            <td>:</td>
                            <td>' . $data_header['pelabuhan'] . '</td>
                        </tr>
                        <tr>
                            <td>Jam</td>
                            <td>:</td>
                            <td>' . $data_header['jam'] . '</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';

        // Reset pointer to the first row
        mysqli_data_seek($result_transaksi, 0);

        $html .= '
        <div class="box" style="margin-top: 30px;">
            <table width="100%" style="font-size: 17px;">
                <tr style="background-color: #88D66C">
                    <td width="10%" style="text-align: center;">No</td>
                    <td width="35%" style="text-align: center;">Jenis Tiket</td>
                    <td width="20%" style="text-align: center;">Tarif</td>
                    <td width="15%" style="text-align: center;">Produksi</td>
                    <td width="20%" style="text-align: center; padding: 5px;">Total</td>
                </tr>';

        
        while ($data_transaksi = mysqli_fetch_array($result_transaksi)) {
            $total = $data_transaksi['tarif'] * $data_transaksi['produksi'];
            $subtotal += $total;
            
            $html .= '
            <tr>
                <td style="text-align: center; padding: 5px;">' . $no++ . '</td>
                <td style="padding: 5px;">' . $data_transaksi['nama_tiket'] . '</td>
                <td style="padding: 5px 0px 5px 25px;">Rp ' . number_format($data_transaksi['tarif'], 0, ',', '.') . '</td>
                <td style="text-align: center; padding: 5px;">' . $data_transaksi['produksi'] . '</td>
                <td style="padding: 5px 0px 5px 20px;">Rp ' . number_format($total, 0, ',', '.') . '</td>
            </tr>';
        }

        $html .= '
       <tr style="background-color: #FFFF00">
            <td></td>
            <td style="padding: 5px;">Subtotal</td>
            <td></td>
            <td></td>
            <td style="padding: 5px 0px 5px 20px;">Rp ' . number_format($subtotal, 0, ',', '.') . '</td>
        </tr>';

        mysqli_stmt_bind_param($stmt_trans, "i", $id_transaksi);
        mysqli_stmt_execute($stmt_trans);
        $result_pengeluaran = mysqli_stmt_get_result($stmt_trans);
        $total_keluar = 0;
        $subtotal_keluar = 0; 
        while ($rec = mysqli_fetch_array($result_pengeluaran)){
            $total_keluar = $rec['tarif'] * $rec['produksi'];
            $subtotal_keluar += $total_keluar;
        $html .='
        <tr>
            <td style="text-align: center; padding: 5px;">' . $no++ . '</td>
            <td style="padding: 5px;">'. $rec['nama_tiket'] .'</td>
            <td style="padding: 5px 0px 5px 25px;">Rp '. number_format($rec['tarif'], 0, ',', '.') .'</td>
            <td style="text-align: center; padding: 5px;">' . $rec['produksi'] . '</td>
            <td style="padding: 5px 0px 5px 20px;">Rp '.number_format($total_keluar, 0, ',','.') .'</td>
        </tr>';
        }

        $html .=
        '
        <tr style="background-color: yellow">
            <td></td>
            <td style="padding: 5px;">Total Pendapatan</td>
            <td></td>
            <td></td>
            <td style="padding: 5px 0px 5px 20px;">Rp ' . number_format($subtotal - $subtotal_keluar, 0, ',', '.') . '</td>
        </tr>
        </table>
        <p style="margin-top: 20px;">Yang membuat user</p>';
    } else {
        die('Query Error: ' . mysqli_error($koneksi));
    }
    
    mysqli_stmt_close($stmt);
} else {
    die('Prepare Statement Error: ' . mysqli_error($koneksi));
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
ob_end_clean();

$dompdf->render();
$dompdf->stream("laporan.pdf", array("Attachment" => 0));

?>
