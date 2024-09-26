 
 <?php
require_once 'Producto.php';
session_start();

// Inicializar variables para el formulario
$codigo = $nombre = $descripcion = $precioUnitario = $cantidad = '';
$modoEdicion = false;

// Cargar datos del producto para editar
if (isset($_GET['editar'])) {
    $indiceEdicion = $_GET['editar'];
    $producto = $_SESSION['productos'][$indiceEdicion];
    $codigo = $producto->getCodigo();
    $nombre = $producto->getNombre();
    $descripcion = $producto->getDescripcion();
    $precioUnitario = $producto->getPrecioUnitario();
    $cantidad = $producto->getCantidad();
    $modoEdicion = true;
}

// Función para actualizar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_producto'])) {
    $indiceEdicion = $_POST['indice'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Actualizar el producto en la sesión
    $_SESSION['productos'][$indiceEdicion] = new Producto($codigo, $nombre, $descripcion, $precioUnitario, $cantidad);
    
    // Redireccionar a la página principal después de la actualización
    header('Location: productos.php');
    exit();
}

// Variables para la plantilla
$titulo = "Editar Producto";
$contenido = '
<h2>Actualizar Producto</h2>
<form method="POST">
    <input type="hidden" name="indice" value="' . htmlspecialchars($indiceEdicion) . '">
    <label>Código: <input type="text" name="codigo" value="' . htmlspecialchars($codigo) . '" required></label><br>
    <label>Nombre: <input type="text" name="nombre" value="' . htmlspecialchars($nombre) . '" required></label><br>
    <label>Descripción: <input type="text" name="descripcion" value="' . htmlspecialchars($descripcion) . '" required></label><br>
    <label>Precio Unitario: <input type="number" name="precio" step="0.01" value="' . htmlspecialchars($precioUnitario) . '" required></label><br>
    <label>Cantidad: <input type="number" name="cantidad" value="' . htmlspecialchars($cantidad) . '" required min="1" value="1"></label><br>
    <button type="submit" name="actualizar_producto">Actualizar Producto</button>
</form>
';

// Incluir la plantilla
include 'template.php';
?>

