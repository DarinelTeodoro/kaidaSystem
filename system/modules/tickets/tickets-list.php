<?php
include('../../model/conexion.php');


try {
    $filter = 'all';
    $tickets = query_all_comandas($filter);
    $data = [];

    if ($tickets) {
        foreach ($tickets as $ticket) {
            $icon_estado = $ticket['estado'] == 1 ? '<div class="ps-2 pe-2 p-1 bg-warning rounded d-flex align-items-center"><i class="fi fi-tr-grill"></i><span class="ms-2" style="font-size: 0.8rem;">En Proceso</span></div>' : ($ticket['estado'] == 2 ? '<div class="ps-2 pe-2 p-1 bg-success text-light rounded d-flex align-items-center"><i class="fi fi-tr-holding-hand-dinner"></i><span class="ms-2" style="font-size: 0.8rem;">Preparado</span></div>' : '<div class="ps-2 pe-2 p-1 bg-danger text-light rounded d-flex align-items-center"><i class="fi fi-tr-octagon-xmark"></i><span class="ms-2" style="font-size: 0.8rem;">Cancelado</span></div>');
            $btn_edit = $ticket['estado'] == 1 ? '<button type="button" class="btn-edit"><i class="fi fi-tr-pencil"></i></button>' : '';
            $data[] = [
                'id' => $ticket['id'],
                'tipo' => $ticket['tipo'],
                'cliente' => $ticket['cliente'],
                'estado' => $icon_estado,
                'creado' => $ticket['creado'],
                'actualizado' => $ticket['actualizado'],
                'finalizado' => $ticket['finalizado'],
                'costo_envio' => $ticket['costo_envio'],
                'motivo_cancelacion' => $ticket['motivo_cancelacion'],
                'mesero' => $ticket['mesero'],
                'button' => $btn_edit
            ];
        }
    }

    echo json_encode(['data' => $data]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
