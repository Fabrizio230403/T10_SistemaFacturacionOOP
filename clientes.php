<?php
require_once 'Modelo/Cliente.php';
session_start();

// Inicializar la sesión de clientes si no existe
if (!isset($_SESSION['clientes'])) {
    $_SESSION['clientes'] = [];
}

$index ='';
$cliente='';

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

    $_SESSION['clientes'] = array_values($_SESSION['clientes']);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Función para actualizar el cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_cliente'])) {
    $indiceEdicion = $_POST['indice'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Actualizar el cliente en la sesión
    $_SESSION['clientes'][$indiceEdicion] = new Cliente($nombre, $apellido, $dni, $direccion, $telefono);
  
    header('Location: clientes.php');
    exit();
}


$titulo = "Gestión de Clientes";

$pagina = "clientes";

$contenido = '
<h2>Listado de Clientes</h2>

<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#agregarClienteModal">
    <i class="fas fa-plus"></i>&nbspNuevo Registro 
</button>

<!-- Modal -->
<div class="modal fade" id="agregarClienteModal" tabindex="-1" role="dialog" aria-labelledby="agregarClienteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarClienteModalLabel">Agregar Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" required class="form-control" id="dni">
            </div>
            <div class="form-group">
                <label for="nombre">Nombres:</label>
                <input type="text" name="nombre" required class="form-control" id="nombre">
            </div>
            <div class="form-group">
                <label for="apellido">Apellidos:</label>
                <input type="text" name="apellido" required class="form-control" id="apellido">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" required class="form-control" id="direccion">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required class="form-control" id="telefono">
            </div>
            <button type="submit" name="agregar_cliente" class="btn btn-primary">Agregar Cliente</button>
        </form>
      </div>
    </div>
  </div>
</div>
 

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
                                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarClienteModal-' . $index . '">Editar</button>
                              </td>
                            </tr>';

          // Modal para editar cliente
          $contenido .= '
          <div class="modal fade" id="editarClienteModal-' . $index . '" tabindex="-1" role="dialog" aria-labelledby="editarClienteModalLabel-' . $index . '" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editarClienteModalLabel-' . $index . '">Editar Cliente</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="POST">
                      <input type="hidden" name="indice" value="' . $index . '">
                      <div class="form-group">
                          <label for="dni-' . $index . '">DNI:</label>
                          <input type="text" name="dni" required class="form-control" id="dni-' . $index . '" value="' . htmlspecialchars($cliente->getDni()) . '">
                      </div>
                      <div class="form-group">
                          <label for="nombre-' . $index . '">Nombre:</label>
                          <input type="text" name="nombre" required class="form-control" id="nombre-' . $index . '" value="' . htmlspecialchars($cliente->getNombre()) . '">
                      </div>
                      <div class="form-group">
                          <label for="apellido-' . $index . '">Apellido:</label>
                          <input type="text" name="apellido" required class="form-control" id="apellido-' . $index . '" value="' . htmlspecialchars($cliente->getApellido()) . '">
                      </div>
                      <div class="form-group">
                          <label for="direccion-' . $index . '">Dirección:</label>
                          <input type="text" name="direccion" required class="form-control" id="direccion-' . $index . '" value="' . htmlspecialchars($cliente->getDireccion()) . '">
                      </div>
                      <div class="form-group">
                          <label for="telefono-' . $index . '">Teléfono:</label>
                          <input type="text" name="telefono" required class="form-control" id="telefono-' . $index . '" value="' . htmlspecialchars($cliente->getTelefono()) . '">
                      </div>
                      <button type="submit" name="actualizar_cliente" class="btn btn-success">Actualizar Cliente</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        ';
      }

$contenido .= '
    </tbody>
</table>';

// Incluir la plantilla
include 'template.php';
?>
