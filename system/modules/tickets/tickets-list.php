<?php
include('../../model/conexion.php');
header('Content-Type: application/json');

try {
    $filter = 'all';
    $tickets = query_all_comandas($filter);
    $data = [];

    if ($tickets) {
        foreach ($tickets as $ticket) {
            $column['id'] = $ticket['id'];
            $column['tipo'] = $ticket['tipo'];
            $column['cliente'] = $ticket['cliente'];
            $column['estado'] = $ticket['estado'];
            $column['creado'] = $ticket['creado'];
            $column['actualizado'] = $ticket['actualizado'];
            $column['finalizado'] = $ticket['finalizado'];
            $column['costo_envio'] = $ticket['costo_envio'];
            $column['motivo_cancelacion'] = $ticket['motivo_cancelacion'];
            $column['mesero'] = $ticket['mesero'];
            $data[] = $column;
        }
    }

    echo json_encode(['data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
