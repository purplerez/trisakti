<?php
ob_start();
include('config.php');
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html = '
<img src="img/kapal.png" style="float: left; height: 60px">

<div style="text-align: center; margin-top: 20px;">
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
              WHERE t.id = ?";

// Prepare statement
$stmt = mysqli_prepare($koneksi, $sql_trans);

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
        <div class="box" style="margin-top: 60px;">
            <table width="100%" style="font-size: 17px;">
                <tr style="margin-bottom: 15px;">
                    <td width="13%" style="text-align: center; padding-bottom: 15px;">No</td>
                    <td width="32%" style="text-align: center; padding-bottom: 15px;">Jenis Tiket</td>
                    <td width="20%" style="text-align: center; padding-bottom: 15px;">Tarif</td>
                    <td width="15%" style="text-align: center; padding-bottom: 15px;">Produksi</td>
                    <td width="20%" style="text-align: center; padding-bottom: 15px;">Total</td>
                </tr>';

        
        while ($data_transaksi = mysqli_fetch_array($result_transaksi)) {
            $total = $data_transaksi['tarif'] * $data_transaksi['produksi'];
            $subtotal += $total;
            
            $html .= '
            <tr>
                <td style="text-align: center; padding-bottom: 10px;">' . $no++ . '</td>
                <td style="padding-bottom: 10px;">' . $data_transaksi['nama_tiket'] . '</td>
                <td style="text-align: center; padding-bottom: 10px;">Rp ' . number_format($data_transaksi['tarif'], 0, ',', '.') . '</td>
                <td style="text-align: center; padding-bottom: 10px;">' . $data_transaksi['produksi'] . '</td>
                <td style="text-align: center; padding-bottom: 10px;">Rp ' . number_format($total, 0, ',', '.') . '</td>
            </tr>';
        }

        $html .= '
       <tr>
            <td></td>
            <td style="padding-bottom: 10px;">Subtotal</td>
            <td></td>
            <td></td>
            <td style="text-align: center; padding-bottom: 10px;">Rp ' . number_format($subtotal, 0, ',', '.') . '</td>
        </tr>
        <tr>
            <td style="text-align: center; padding-bottom: 10px;">' . $no++ . '</td>
            <td style="padding-bottom: 10px;">Bea Cetak</td>
            <td style="text-align: center; padding-bottom: 10px;">Rp 5.000</td>
            <td style="text-align: center; padding-bottom: 10px;">2</td>
            <td style="text-align: center; padding-bottom: 10px;">Rp 10.000</td>
        </tr>
        <tr>
            <td style="text-align: center; padding-bottom: 10px;">' . $no++ . '</td>
            <td style="padding-bottom: 10px;">Bea Sandar</td>
            <td style="text-align: center; padding-bottom: 10px;">Rp 5.000</td>
            <td style="text-align: center; padding-bottom: 10px;">2</td>
            <td style="text-align: center; padding-bottom: 10px;">Rp 10.000</td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-bottom: 10px;">Total Pendapatan</td>
            <td></td>
            <td></td>
            <td style="text-align: center; padding-bottom: 10px;">Rp ' . number_format($subtotal - 20000, 0, ',', '.') . '</td>
        </tr>
        </table>
        <p style="margin-top: 50px;">Yang membuat user</p>';
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
