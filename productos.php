<?php
require_once 'Producto.php';
session_start();

// Inicializar la sesión de productos si no existe
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

// Función para agregar un producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Crear el producto y agregarlo a la sesión
    $producto = new Producto($codigo, $nombre, $descripcion, $precioUnitario, $cantidad);
    $_SESSION['productos'][] = $producto;
}

// Función para eliminar un producto
if (isset($_GET['eliminar'])) {
    $index = $_GET['eliminar'];
    unset($_SESSION['productos'][$index]);
    // Reindexar el array para evitar huecos
    $_SESSION['productos'] = array_values($_SESSION['productos']);
}

// Variables para la plantilla
$titulo = "Gestión de Productos";
$contenido = '
<h2>Agregar Producto</h2>
<form method="POST">
    <label>Código: <input type="text" name="codigo" required></label><br>
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Descripción: <input type="text" name="descripcion" required></label><br>
    <label>Precio Unitario: <input type="number" name="precio" step="0.01" required></label><br>
    <label>Cantidad: <input type="number" name="cantidad" required></label><br>
    <button type="submit" name="agregar_producto">Agregar Producto</button>
</form>

<h2>Listado de Productos</h2>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        ' . (empty($_SESSION['productos']) ? '<tr><td colspan="7">No hay productos agregados.</td></tr>' : '') . '
        ';

foreach ($_SESSION['productos'] as $index => $producto) {
    $contenido .= '<tr>
                    <td>' . htmlspecialchars($producto->getCodigo()) . '</td>
                    <td>' . htmlspecialchars($producto->getNombre()) . '</td>
                    <td>' . htmlspecialchars($producto->getDescripcion()) . '</td>
                    <td>' . htmlspecialchars($producto->getPrecioUnitario()) . '</td>
                    <td>' . htmlspecialchars($producto->getCantidad()) . '</td>
                    <td>' . htmlspecialchars($producto->calcularTotal()) . '</td>
                    <td><a href="?eliminar=' . $index . '" class="btn btn-danger">Eliminar</a></td>
                  </tr>';
}

$contenido .= '
    </tbody>
</table>
';

// Incluir la plantilla
include 'template.php';
?>
