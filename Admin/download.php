<?php
require '../vendor/autoload.php';
require ('../user/config.php'); 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$id_transaksi = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id_transaksi === null) {
    die('Error: ID transaksi tidak ditemukan.');
}


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$sheet->setCellValue('A1', 'LAPORAN PRODUKSI KAPAL');
$sheet->setCellValue('A2', 'KMP. TRIMAS LAILA');
$sheet->mergeCells('A1:E1');
$sheet->mergeCells('A2:E2');

$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

$sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2:E2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);



$sql_trans = "SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, j.nama_tiket, j.tarif, d.produksi 
              FROM tb_transaksi AS t 
              JOIN tb_detail_trans AS d ON t.id = d.id_transaksi 
              JOIN tb_jenistiket AS j ON d.id_tiket = j.id
              WHERE t.id = ? AND j.status = '1'";

$sql_trans_pengeluaran = "SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, j.nama_tiket, j.tarif, d.produksi 
                          FROM tb_transaksi AS t 
                          JOIN tb_detail_trans AS d ON t.id = d.id_transaksi 
                          JOIN tb_jenistiket AS j ON d.id_tiket = j.id
                          WHERE t.id = ? AND j.status = '0'";


$stmt = mysqli_prepare($koneksi, $sql_trans);
mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
mysqli_stmt_execute($stmt);
$result_transaksi = mysqli_stmt_get_result($stmt);

if ($result_transaksi) {
    $subtotal = 0;
    $no = 1;

   
    $data_header = mysqli_fetch_array($result_transaksi);
    
    
    $sheet->setCellValue('A4', 'Tanggal')->setCellValue('B4', ': ' . date('d-m-Y', strtotime($data_header['tanggal'])));
    $sheet->setCellValue('A5', 'Trip')->setCellValue('B5', ': ' . $data_header['trip']);
    $sheet->setCellValue('D4', 'Pelabuhan')->setCellValue('E4', ': ' . $data_header['pelabuhan']);
    $sheet->setCellValue('D5', 'Jam')->setCellValue('E5', ': ' . $data_header['jam']);

    $sheet->setCellValue('A7', 'No');
    $sheet->setCellValue('B7', 'Jenis Tiket');
    $sheet->setCellValue('C7', 'Tarif');
    $sheet->setCellValue('D7', 'Produksi');
    $sheet->setCellValue('E7', 'Total');

    $rowIndex = 8; 

    
    mysqli_data_seek($result_transaksi, 0); 
    while ($data_transaksi = mysqli_fetch_array($result_transaksi)) {
        $total = $data_transaksi['tarif'] * $data_transaksi['produksi'];
        $subtotal += $total;

        
        $sheet->setCellValue('A' . $rowIndex, $no++);
        $sheet->setCellValue('B' . $rowIndex, $data_transaksi['nama_tiket']);
        $sheet->setCellValue('C' . $rowIndex, 'Rp ' . number_format($data_transaksi['tarif'], 0, ',', '.'));
        $sheet->setCellValue('D' . $rowIndex, $data_transaksi['produksi']);
        $sheet->setCellValue('E' . $rowIndex, 'Rp ' . number_format($total, 0, ',', '.'));

        $rowIndex++;
    }

    $sheet->setCellValue('B' . $rowIndex, 'Subtotal');
    $sheet->setCellValue('E' . $rowIndex, 'Rp ' . number_format($subtotal, 0, ',', '.'));
    $rowIndex++;

    $stmt_pengeluaran = mysqli_prepare($koneksi, $sql_trans_pengeluaran);
    mysqli_stmt_bind_param($stmt_pengeluaran, "i", $id_transaksi);
    mysqli_stmt_execute($stmt_pengeluaran);
    $result_pengeluaran = mysqli_stmt_get_result($stmt_pengeluaran);

    $total_keluar = 0;
    $subtotal_keluar = 0;

    while ($rec = mysqli_fetch_array($result_pengeluaran)) {
        $total_keluar = $rec['tarif'] * $rec['produksi'];
        $subtotal_keluar += $total_keluar;

        $sheet->setCellValue('A' . $rowIndex, $no++);
        $sheet->setCellValue('B' . $rowIndex, $rec['nama_tiket']);
        $sheet->setCellValue('C' . $rowIndex, 'Rp ' . number_format($rec['tarif'], 0, ',', '.'));
        $sheet->setCellValue('D' . $rowIndex, $rec['produksi']);
        $sheet->setCellValue('E' . $rowIndex, 'Rp ' . number_format($total_keluar, 0, ',', '.'));

        $rowIndex++;
    }

    
    $sheet->setCellValue('B' . $rowIndex, 'Total Pendapatan');
    $sheet->setCellValue('E' . $rowIndex, 'Rp ' . number_format($subtotal - $subtotal_keluar, 0, ',', '.'));

    $sheet->getStyle('A7:E7')->getFont()->setBold(true);
    $sheet->getStyle('A7:E7')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('C6E2B3');

    $sheet->getStyle('A7:E7')->getFont()->setBold(true);
    $sheet->getStyle('A7:E'.$rowIndex)->applyFromArray([
        'borders'=> [
            'allBorders' => [
                'borderStyle' => \PhpOffice\phpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);


    $sheet->getStyle('A21:E21')->getFont()->setBold(true);
    $sheet->getStyle('A21:E21')->getFill()
        ->setFillType(\phpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFF00');

    $sheet->getStyle('A24:E24')->getFont()->setBold(true);
    $sheet->getStyle('A24:E24')->getFill()
        ->setFillType(\phpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFF00');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
}


$filename = 'Laporan_Produksi_Kapal.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
