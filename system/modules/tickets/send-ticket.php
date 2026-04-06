<?php
session_start();
header('Content-Type: application/json');
include('../../model/conexion.php');
date_default_timezone_set('America/Mexico_City');

// Obtener los datos del formulario
$input = json_decode(file_get_contents('php://input'), true);

$data_user = query_user($_SESSION['USERLOGED']);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

$conexion = new Conexion();

try {
    // Iniciar transacción
    $conexion->beginTransaction();
    $fecha = date('Y-m-d H:i:s');
    
    // 1. Insertar la comanda principal
    $stmt = $conexion->prepare("
        INSERT INTO comandas (
            tipo, 
            cliente, 
            estado, 
            creado, 
            actualizado,
            costo_envio,
            mesero
        ) VALUES (:tipo, :cliente, 1, :creado, :actualizado, :costo_envio, :mesero)
    ");
    
    $stmt->execute([
        ':tipo' => $input['type_delivery'], 
        ':cliente' => $input['client'], 
        ':creado' => $fecha, 
        ':actualizado' => $fecha, 
        ':costo_envio' => $input['delivery_cost'], 
        ':mesero' => $data_user['id']
    ]);
    
    $comandaId = $conexion->lastInsertId();
    
    // 2. Insertar cada item del carrito
    $stmtItem = $conexion->prepare("
        INSERT INTO comanda_productos (
            id_comanda,
            id_item,
            tipo,
            producto,
            precio_base,
            id_variante_extra,
            variante,
            incremento,
            precio_final,
            cantidad,
            nota
        ) VALUES (:id_comanda, :id_item, :tipo, :producto, :precio_base, :id_vaex, :variante, :incremento, :precio_final, :cantidad, :nota)
    ");
    
    $stmtComboProducto = $conexion->prepare("
        INSERT INTO comanda_combo_productos (
            item_id,
            combo_item_id,
            item_nombre,
            grupo,
            tipo
        ) VALUES (:item_id, :combo_item_id, :item_nombre, :grupo, :tipo)
    ");
    
    foreach ($input['items'] as $item) {
        // Determinar datos de variante si existe
        $id_variante = null;
        $nombre_variante = null;
        $incremento = 0;
        $precio_origin = $item['precio_original'];
        
        if ($item['tipo'] === 'variante' && isset($item['variante'])) {
            $id_variante = $item['variante']['id'] ?? null;
            $nombre_variante = $item['variante']['nombre'] ?? null;
            $incremento = $item['variante']['incremento'] ?? 0;

            $precio_origin = $item['variante']['precio_producto'];
        }

        // Insertar item principal (producto, variante o combo)
        $item_final = ($precio_origin + $incremento) * $item['cantidad'];
        $stmtItem->execute([
            ':id_comanda' => $comandaId,
            ':id_item' => $item['id'],
            ':tipo' => $item['tipo'],
            ':producto' => $item['nombre'],
            ':precio_base' => $precio_origin,
            ':id_vaex' => $id_variante,
            ':variante' => $nombre_variante,
            ':incremento' => $incremento,
            ':precio_final' => $item_final,
            ':cantidad' => $item['cantidad'],
            ':nota' => $item['comentarios'] ?? null
        ]);
        
        $itemId = $conexion->lastInsertId();
        
        // Insertar extras si existen (como items separados relacionados)
        if (!empty($item['extras'])) {
            foreach ($item['extras'] as $extra) {
                $extra_final = $extra['precio'] * $extra['cantidad'];
                $stmtItem->execute([
                    ':id_comanda' => $comandaId,
                    ':id_item' => $itemId,
                    ':tipo' => 'extra',
                    ':producto' => $extra['nombre'],
                    ':precio_base' => $extra['precio'],
                    ':id_vaex' => $extra['id'],
                    ':variante' => null,
                    'incremento' => 0,
                    ':precio_final' => $extra_final,
                    ':cantidad' => $extra['cantidad'],
                    ':nota' => null
                ]);
            }
        }
        
        // Si es combo, insertar los productos del combo
        if ($item['tipo'] === 'combo' && !empty($item['productos'])) {
            foreach ($item['productos'] as $producto) {
                $stmtComboProducto->execute([
                    ':item_id' => $itemId,
                    ':combo_item_id' => $producto['id'],
                    ':item_nombre' => $producto['nombre'],
                    ':grupo' => $producto['grupo'],
                    ':tipo' => $producto['tipo']
                ]);
            }
        }
    }
    
    // Confirmar transacción
    $conexion->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Comanda guardada correctamente',
        'comanda_id' => $comandaId
    ]);
    
} catch(Exception $e) {
    // Revertir transacción en caso de error
    $conexion->rollBack();
    echo json_encode([
        'success' => false, 
        'message' => 'Error al guardar: ' . $e->getMessage()
    ]);
}
?>