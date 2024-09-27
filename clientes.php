<?php
require_once 'Cliente.php';
session_start();

// Inicializar la sesión de clientes si no existe
if (!isset($_SESSION['clientes'])) {
    $_SESSION['clientes'] = [];
}

// Función para agregar un cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_cliente'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Crear el cliente y agregarlo a la sesión
    $cliente = new Cliente($nombre, $apellido, $dni, $direccion, $telefono);
    $_SESSION['clientes'][] = $cliente;

      // Redirigir a la misma página para evitar el reenvío del formulario
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
}

// Función para eliminar un cliente
if (isset($_GET['eliminar'])) {
    $index = $_GET['eliminar'];
    unset($_SESSION['clientes'][$index]);
    // Reindexar el array para evitar huecos
    $_SESSION['clientes'] = array_values($_SESSION['clientes']);

    // Redirigir después de eliminar para evitar problemas de recarga
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Variables para la plantilla
$titulo = "Gestión de Clientes";

$pagina = "clientes";//Esto sirve para que el color azulito este en el menu que estamos

$contenido = '
<h2>Agregar Cliente</h2>
<form method="POST">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Apellido: <input type="text" name="apellido" required></label><br>
    <label>DNI: <input type="text" name="dni" required></label><br>
    <label>Dirección: <input type="text" name="direccion" required></label><br>
    <label>Teléfono: <input type="text" name="telefono" required></label><br>
    <button type="submit" name="agregar_cliente">Agregar Cliente</button>
</form>

<h2>Listado de Clientes</h2>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        ' . (empty($_SESSION['clientes']) ? '<tr><td colspan="6">No hay clientes agregados.</td></tr>' : '') . '
';

foreach ($_SESSION['clientes'] as $index => $cliente) {
    $contenido .= '<tr>
                    <td>' . htmlspecialchars($cliente->getNombre()) . '</td>
                    <td>' . htmlspecialchars($cliente->getApellido()) . '</td>
                    <td>' . htmlspecialchars($cliente->getDni()) . '</td>
                    <td>' . htmlspecialchars($cliente->getDireccion()) . '</td>
                    <td>' . htmlspecialchars($cliente->getTelefono()) . '</td>
                    <td>
                        <a href="?eliminar=' . $index . '" class="btn btn-danger">Eliminar</a>
                        <a href="editarClientes.php?editar=' . $index . '" class="btn btn-warning">Editar</a>
                    </td>
                  </tr>';
}

$contenido .= '
    </tbody>
</table>
';

// Incluir la plantilla
include 'template.php';
?>
