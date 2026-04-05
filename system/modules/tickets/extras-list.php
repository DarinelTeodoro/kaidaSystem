<?php
include('../../model/conexion.php');

$conexion = new Conexion();
$query = query_all_extras('all_registers');


$extras = [];
if ($query) {
    foreach ($query as $extra) {
        $extras[] = [
        'id' => $extra['id'],
        'nombre' => $extra['extra'],
        'precio' => $extra['precio']
    ];
    }
}

header('Content-Type: application/json');
echo json_encode($extras);
?>