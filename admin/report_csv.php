<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Se usa Spreadsheet para la creación de la hoja y xlsx para el formato 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Se obtiene el libro y luego la hoja en la cual se escribirá

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Indicador de encabezados y celdas

$sheet->setCellValue('A1', 'ID Venta')
    ->setCellValue('B1', 'Cliente')
    ->setCellValue('C1', 'Fecha')
    ->setCellValue('D1', 'Total');

// Contador de fila

$rowNum = 2;

// COnsulta a la base de datos

$stmt = $pdo->query("SELECT v.id, u.nombre as cliente, v.fecha, v.total 
                    FROM ventas v 
                    JOIN usuarios u ON u.id=v.id_usuario 
                    ORDER BY v.id DESC");

//Se genera un bucle para el llenado de datos

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $sheet->setCellValue("A{$rowNum}", $row['id'])
        ->setCellValue("B{$rowNum}", $row['cliente'])
        ->setCellValue("C{$rowNum}", $row['fecha'])
        ->setCellValue("D{$rowNum}", $row['total']);
    $rowNum++;
}

//Se genera el opción de descarga del navegador indicando que es un excel y qe lo guarde siempre
// a nombre de ventas.xlsx

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ventas.xlsx"');

// Se crea un objeto que permite que el spreadsheets se convierta en xlsx.
$writer = new Xlsx($spreadsheet);
//Esto permite para crear el archivo sin otros archivos temporales
$writer->save('php://output');
// Termina la ejecución
exit;
?>



