<?php
require_once __DIR__ . '/../lib/admin_guard.php';
require_once __DIR__ . '/../config/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ventas.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID Venta','Cliente','Fecha','Total']);

$stmt = $pdo->query("SELECT v.id, u.nombre as cliente, v.fecha, v.total FROM ventas v JOIN usuarios u ON u.id=v.id_usuario ORDER BY v.id DESC");
while($row = $stmt->fetch(PDO::FETCH_NUM)){
    fputcsv($output, $row);
}
fclose($output);
exit;
?>