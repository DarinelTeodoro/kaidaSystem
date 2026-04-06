<?php
date_default_timezone_set('America/Mexico_City');
class Conexion extends PDO
{
    private $tipo_de_base = 'mysql';
    private $host = 'localhost';
    private $nombre_de_base = 'kaida_system';
    private $usuario = 'root';
    private $contrasena = '';

    public function __construct()
    {
        try {
            parent::__construct($this->tipo_de_base . ':host=' . $this->host . ';dbname=' . $this->nombre_de_base, $this->usuario, $this->contrasena);
        } catch (PDOException $e) {
            echo "Ha surgido un error y no se puede conectar a la B.D. DETALLE: " . $e->getMessage();
        }
    }
}


//----------------------------------------------------------------------- TABLA USUARIOS ---------------------------------------------------------
function query_user_byId($user)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE id = :user AND status = 1");
    $query->bindParam(':user', $user);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_user($user)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE user = :user AND status = 1");
    $query->bindParam(':user', $user);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_users()
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE status = 1 ORDER BY name ASC");
    $query->execute();
    $count = $query->rowCount();

    if ($count > 0) {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


//----------------------------------------------------------------------- TABLA CATEGORIAS ---------------------------------------------------------
function query_category($categoria)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_categorias WHERE id = :id");
    $query->bindParam(':id', $categoria);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_categories($results = 'all_registers')
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_categorias";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all_registers':
            $sql .= " ORDER BY
            CASE 
                WHEN categoria = 'Otros' THEN 1 
                ELSE 0 
            END,
            categoria ASC";
            break;

        case 'active_registers':
            $sql .= " WHERE disponibilidad = 1 ORDER BY
            CASE 
                WHEN categoria = 'Otros' THEN 1 
                ELSE 0 
            END,
            categoria ASC";
            break;

        default:
            $sql .= " ORDER BY
            CASE 
                WHEN categoria = 'Otros' THEN 1 
                ELSE 0 
            END,
            categoria ASC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        //error_log("Error en query_all_categories: " . $e->getMessage());
        return false;
    }
}




//----------------------------------------------------------------------- TABLA COMBOS ---------------------------------------------------------
function query_combo($combo)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_combos WHERE id = :id");
    $query->bindParam(':id', $combo);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_combos($results = 'all_registers')
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_combos";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all_registers':
            $sql .= " ORDER BY combo ASC";
            break;

        case 'active_registers':
            $sql .= " WHERE disponibilidad = 1 ORDER BY combo ASC";
            break;

        default:
            $sql .= " ORDER BY combo ASC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}



function query_section($section)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_combos_secciones WHERE id = :id");
    $query->bindParam(':id', $section);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function query_all_sections($combo)
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_combos_secciones WHERE id_combo = :id";

    try {
        $query = $conexion->prepare($sql);
        $query->bindParam(':id', $combo);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}



//----------------------------------------------------------------------- TABLA PRODUCTOS ---------------------------------------------------------
function query_product($producto)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_productos WHERE id = :id");
    $query->bindParam(':id', $producto);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function query_all_products($results = 'all_registers')
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_productos";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all_registers':
            $sql .= " ORDER BY producto ASC";
            break;

        case 'active_registers':
            $sql .= " WHERE disponibilidad = 1 ORDER BY producto ASC";
            break;

        default:
            $sql .= " ORDER BY producto ASC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}



//----------------------------------------------------------------------- TABLA VARIANTES ---------------------------------------------------------
function query_variant($variante)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_variantes WHERE id = :id");
    $query->bindParam(':id', $variante);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}


function query_variants($product)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_variantes WHERE id_producto = :id");
    $query->bindParam(':id', $product);
    $query->execute();
    $count = $query->rowCount();

    if ($count > 0) {
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_variants($results = 'all_registers')
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_variantes";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all_registers':
            $sql .= " ORDER BY variante ASC";
            break;

        case 'active_registers':
            $sql .= " WHERE disponibilidad = 1 ORDER BY variante ASC";
            break;

        default:
            $sql .= " ORDER BY variante ASC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}


//----------------------------------------------------------------------- TABLA EXTRAS ---------------------------------------------------------
function query_extra($extra)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM menu_extras WHERE id = :id");
    $query->bindParam(':id', $extra);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_extras($results = 'all_registers')
{
    $conexion = new Conexion();

    $sql = "SELECT * FROM menu_extras";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all_registers':
            $sql .= " ORDER BY extra ASC";
            break;

        case 'active_registers':
            $sql .= " WHERE disponibilidad = 1 ORDER BY extra ASC";
            break;

        default:
            $sql .= " ORDER BY extra ASC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}




//----------------------------------------------------------------------- TABLA COMANDAS ---------------------------------------------------------
function query_comanda($comanda)
{
    $conexion = new Conexion();
    $query = $conexion->prepare("SELECT * FROM comandas WHERE id = :id");
    $query->bindParam(':id', $comanda);
    $query->execute();
    $count = $query->rowCount();

    if ($count == 1) {
        return $query->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

function query_all_comandas($results = 'all')
{
    $conexion = new Conexion();
    $fecha = date('Y-m-d');

    $sql = "SELECT * FROM comandas";

    // Agregar condiciones según el parámetro
    switch ($results) {
        case 'all':
            $sql .= " WHERE DATE(creado) = :fecha ORDER BY id DESC";
            break;

        case 'pending':
            $sql .= " WHERE DATE(creado) = :fecha AND estado = 1 ORDER BY actualizado DESC";
            break;

        case 'finished':
            $sql .= " WHERE DATE(creado) = :fecha AND estado = 3 ORDER BY finalizado DESC";
            break;

        default:
            $sql .= " WHERE DATE(creado) = :fecha ORDER BY id DESC";
    }

    try {
        $query = $conexion->prepare($sql);
        $query->execute([':fecha' => $fecha]);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        return !empty($results) ? $results : false;

    } catch (PDOException $e) {
        return false;
    }
}
?>