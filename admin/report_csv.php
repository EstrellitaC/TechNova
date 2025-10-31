<?php
ob_clean();
ob_start();

require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$sheet->setCellValue('A1', 'ID Venta')
        ->setCellValue('B1', 'Cliente')
        ->setCellValue('C1', 'Fecha')
        ->setCellValue('D1', 'Total');

// Datos
$stmt = $pdo->query("SELECT v.id, u.nombre AS cliente, v.fecha, v.total
                    FROM ventas v 
                    JOIN usuarios u ON u.id = v.id_usuario 
                    ORDER BY v.id DESC");

$rowNum = 2;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sheet->setCellValue("A{$rowNum}", $row['id'])
            ->setCellValue("B{$rowNum}", $row['cliente'])
            ->setCellValue("C{$rowNum}", $row['fecha'])
            ->setCellValue("D{$rowNum}", $row['total']);
    $rowNum++;
}

// Limpieza final antes de enviar
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ventas.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;



