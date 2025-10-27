<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$sheet->setCellValue('A1', 'ID Venta')
      ->setCellValue('B1', 'Cliente')
      ->setCellValue('C1', 'Fecha')
      ->setCellValue('D1', 'Total');

$rowNum = 2;

// Obtener datos de la base
$stmt = $pdo->query("SELECT v.id, u.nombre as cliente, v.fecha, v.total 
                     FROM ventas v 
                     JOIN usuarios u ON u.id=v.id_usuario 
                     ORDER BY v.id DESC");

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $sheet->setCellValue("A{$rowNum}", $row['id'])
          ->setCellValue("B{$rowNum}", $row['cliente'])
          ->setCellValue("C{$rowNum}", $row['fecha'])
          ->setCellValue("D{$rowNum}", $row['total']);
    $rowNum++;
}

// Descargar archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ventas.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
