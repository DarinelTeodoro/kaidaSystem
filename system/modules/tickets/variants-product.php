<?php
include('../../model/conexion.php');

$producto_id = (int)$_POST['id_product'];
$producto = query_product($producto_id);
$variantes = query_variants($producto_id);

$prodName = $producto ? $producto['producto'] : 'Producto';
$prodPrecio = $producto ? (float)$producto['precio'] : 0;

if ($variantes) {
  foreach ($variantes as $variante) {
    $varId = (int)$variante['id'];
    $varName = $variante['variante'];
    $inc = (float)$variante['precio_agregado'];
    $precioFinal = $prodPrecio + $inc;
?>
<div>
  <button type="button" class="btn-select-variante" onclick="variant_to_car(<?= $producto_id ?>, '<?= addslashes($producto['producto']) ?>', <?= $varId ?>, '<?= addslashes($varName) ?>', <?= $precioFinal ?>)">
    <span><?= htmlspecialchars($varName) ?> (+$<?= number_format($inc, 2) ?>)</span>
  </button>
</div>
<?php
  }
}
?>
