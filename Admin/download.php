<?php
require '../vendor/autoload.php';
include "support.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$database = new DatabaseConnection();
$conn = $database->conn;

if (isset($_POST['id'])) {
    $id_transaksi = intval($_POST['id']);

    $query = 'SELECT t.tanggal, t.jam, t.pelabuhan, t.trip, j.nama_tiket, j.tarif, d.produksi 
        FROM tb_transaksi AS t 
        JOIN tb_detail_trans AS d ON t.id = d.id_transaksi 
        JOIN tb_jenistiket AS j ON d.id_tiket = j.id
        WHERE t.id = ?';

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_transaksi);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $infoRow = 1;
    $sheet->setCellValue('A'.$infoRow, 'Tanggal:');
    $sheet->setCellValue('B'.$infoRow, 'Jam:');
    $sheet->setCellValue('C'.$infoRow, 'Pelabuhan:');
    $sheet->setCellValue('D'.$infoRow, 'Trip:');
    

    $infoRow++;
    $transactionData = $result->fetch_assoc();
    $sheet->setCellValue('A'.$infoRow, $transactionData['tanggal']);
    $sheet->setCellValue('B'.$infoRow, $transactionData['jam']);
    $sheet->setCellValue('C'.$infoRow, $transactionData['pelabuhan']);
    $sheet->setCellValue('D'.$infoRow, $transactionData['trip']);

    $tableStartRow = $infoRow + 2;
    $sheet->setCellValue('A'.$tableStartRow, 'No');
    $sheet->setCellValue('B'.$tableStartRow, 'Jenis Tiket');
    $sheet->setCellValue('C'.$tableStartRow, 'Tarif');
    $sheet->setCellValue('D'.$tableStartRow, 'Produksi');
    $sheet->setCellValue('E'.$tableStartRow, 'Total');

    $row = $tableStartRow + 1;
    $no = 1;
    $totalTarif = 0;
    $totalProduksi = 0;
    $totalPendapatan = 0;

    $result->data_seek(0);
    while ($rec = $result->fetch_assoc()) {
        $tarif = $rec['tarif'];
        $produksi = $rec['produksi'];
        $total = $tarif * $produksi;

        $sheet->setCellValue('A'.$row, $no++);
        $sheet->setCellValue('B'.$row, $rec['nama_tiket']);
        $sheet->setCellValue('C'.$row, number_format($tarif, 0,'.'));
        $sheet->setCellValue('D'.$row, $produksi);
        $sheet->setCellValue('E'.$row, number_format($total, 0,'.'));

        $totalTarif += $tarif;
        $totalProduksi += $produksi;
        $totalPendapatan += $total;
        $row++;
    }
       // Subtotal row
       $subtotalRow = $row + 1;
       $sheet->setCellValue('B'.$subtotalRow, 'Subtotal:');
       $sheet->setCellValue('E'.$subtotalRow, number_format($totalPendapatan, 0, ',', '.'));
   
       // Bea Cetak row
       $beacetakRow = $subtotalRow + 1;
       $sheet->setCellValue('A'.$beacetakRow, $no++);
       $sheet->setCellValue('B'.$beacetakRow, 'Bea Cetak:');
       $sheet->setCellValueExplicit('C'.$row, number_format($tarif, 0, ',', '.'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
       $sheet->setCellValue('D'.$beacetakRow, $totalProduksi); // Update row to beacetakRow
       $sheet->setCellValueExplicit('E'.$row, number_format($total, 0, ',', '.'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
   
       // Bea Sandar row
       $beasandarRow = $beacetakRow + 1;
       $sheet->setCellValue('A'.$beasandarRow, $no++);
       $sheet->setCellValue('B'.$beasandarRow, 'Bea Sandar:');
       $sheet->setCellValue('C'.$beasandarRow, number_format(345690, 0,'.'));
       $sheet->setCellValue('D'.$beasandarRow, $totalProduksi); // Update row to    beasandarRow
       $sheet->setCellValue('E'.$beasandarRow, number_format($totalProduksi * 34569, 0,',', '.'));
   
       // Total Pendapatan row
       $totalPendapatanRow = $beasandarRow + 1;
       $sheet->setCellValue('B'.$totalPendapatanRow, 'Total Pendapatan:');
       $sheet->setCellValue('E'.$totalPendapatanRow, number_format($totalPendapatan - (90 + 34569) * $totalProduksi, 0, ',', '.'));
   
    $sheet->getStyle('A4:E4')->getFont()->setBold(true);
    $sheet->getStyle('A4:E4')->getFill()
        ->setFillType(\phpOffice\phpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('C6E2B3');

    $sheet->getStyle('A4:E4')->getFont()->setBold(true);
    $sheet->getStyle('A4:E'.$totalPendapatanRow)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ]);

    $sheet->getStyle('A19:E19')->getFont()->setBold(true);
    $sheet->getStyle('A19:E19')->getFill()
        ->setFillType(\phpOffice\phpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFF00');

        $sheet->getStyle('A22:E22')->getFont()->setBold(true);
        $sheet->getStyle('A22:E22')->getFill()
            ->setFillType(\phpOffice\phpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');

    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $filename = 'Laporan_Transaksi_' . $id_transaksi . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
?>
