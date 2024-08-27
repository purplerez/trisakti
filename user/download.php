<?php
require 'vendor/autoload.php'; // Menggunakan autoload Composer
include "support.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['id'])){
    $transaksi = new TransaksiController();
    $id = $_POST['id'];
    $data = $transaksi->edit($id);

    if($data){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header kolom
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Jam');
        $sheet->setCellValue('C1', 'Pelabuhan');
        $sheet->setCellValue('D1', 'Trip');
        $sheet->setCellValue('E1', 'Total Pendapatan');

        // Mengisi data dari database
        $row = 2; // Mulai dari baris kedua
        while($row_data = $data->fetch_assoc()){
            $sheet->setCellValue('A'.$row, $row_dat a['tanggal']);
            $sheet->setCellValue('B'.$row, $row_data['jam']);
            $sheet->setCellValue('C'.$row, $row_data['pelabuhan']);
            $sheet->setCellValue('D'.$row, $row_data['trip']);
            $sheet->setCellValue('E'.$row, number_format($row_data['total_pendapatan'], 0, ',', '.'));
            $row++;
        }

        // Set nama file dan mengirim ke browser untuk di-download
        $filename = "Laporan_Transaksi_".$id.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>
