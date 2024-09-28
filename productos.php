<?php
require_once 'Modelo/Producto.php';
session_start();

// Inicializar la sesión de productos si no existe
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

$index ='';
$producto='';

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

    // Redirigir a la misma página para evitar el reenvío del formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Función para eliminar un producto
if (isset($_GET['eliminar'])) {
    $index = $_GET['eliminar'];
    unset($_SESSION['productos'][$index]);
    // Reindexar el array para evitar huecos, esto permite ajustar los índices
    $_SESSION['productos'] = array_values($_SESSION['productos']);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
    
    header('Location: productos.php');
    exit();
}


// Variables para la plantilla
$titulo = "Gestión de Productos";

$pagina = "productos"; 

$contenido = '
<h2>Listado de Productos</h2>

<!-- Botón para abrir el modal -->
<button type="button" class="btn btn-primary mb-3 float-right" data-toggle="modal" data-target="#agregarProductoModal">
   <i class="fas fa-plus"></i>&nbspNuevo Registro 
</button>

<!-- Modal -->
<div class="modal fade" id="agregarProductoModal" tabindex="-1" role="dialog" aria-labelledby="agregarProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarProductoModalLabel">Agregar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
            <div class="form-group">
                <label for="codigo">Código:</label>
                <input type="text" name="codigo" required class="form-control" id="codigo">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required class="form-control" id="nombre">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" required class="form-control" id="descripcion">
            </div>
            <div class="form-group">
                <label for="precio">Precio Unitario:</label>
                <input type="number" name="precio" step="0.01" required class="form-control" id="precio">
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" required min="1" value="1" class="form-control" id="cantidad">
            </div>
            <button type="submit" name="agregar_producto" class="btn btn-primary">Agregar Producto</button>
        </form>
      </div>
    </div>
  </div>
</div>


 
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
            // Aquí $producto es una instancia de Producto, en caso de que no haya ningún registro  en la tabla.
            if ($producto instanceof Producto) {
                $contenido .= '<tr>
                                <td>' . htmlspecialchars($producto->getCodigo()) . '</td>
                                <td>' . htmlspecialchars($producto->getNombre()) . '</td>
                                <td>' . htmlspecialchars($producto->getDescripcion()) . '</td>
                                <td>' . htmlspecialchars($producto->getPrecioUnitario()) . '</td>
                                <td>' . htmlspecialchars($producto->getCantidad()) . '</td>
                                <td>S/.' . htmlspecialchars(number_format($producto->calcularTotal()),2) . '</td>
                                <td>
                                    <a href="?eliminar=' . $index . '" class="btn btn-danger">Eliminar</a>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarProductoModal-' . $index . '">Editar</button>
                                </td>
                              </tr>
                              
                              <!-- Modal para editar producto -->
                              <div class="modal fade" id="editarProductoModal-' . $index . '" tabindex="-1" role="dialog" aria-labelledby="editarProductoModalLabel-' . $index . '" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="editarProductoModalLabel-' . $index . '">Editar Producto</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <form method="POST">
                                          <input type="hidden" name="indice" value="' . $index . '">
                                          <div class="form-group">
                                              <label for="codigo-' . $index . '">Código:</label>
                                              <input type="text" name="codigo" required class="form-control" id="codigo-' . $index . '" value="' . htmlspecialchars($producto->getCodigo()) . '">
                                          </div>
                                          <div class="form-group">
                                              <label for="nombre-' . $index . '">Nombre:</label>
                                              <input type="text" name="nombre" required class="form-control" id="nombre-' . $index . '" value="' . htmlspecialchars($producto->getNombre()) . '">
                                          </div>
                                          <div class="form-group">
                                              <label for="descripcion-' . $index . '">Descripción:</label>
                                              <input type="text" name="descripcion" required class="form-control" id="descripcion-' . $index . '" value="' . htmlspecialchars($producto->getDescripcion()) . '">
                                          </div>
                                          <div class="form-group">
                                              <label for="precio-' . $index . '">Precio Unitario:</label>
                                              <input type="number" name="precio" step="0.01" required class="form-control" id="precio-' . $index . '" value="' . htmlspecialchars($producto->getPrecioUnitario()) . '">
                                          </div>
                                          <div class="form-group">
                                              <label for="cantidad-' . $index . '">Cantidad:</label>
                                              <input type="number" name="cantidad" required class="form-control" id="cantidad-' . $index . '" value="' . htmlspecialchars($producto->getCantidad()) . '">
                                          </div>
                                          <button type="submit" name="actualizar_producto" class="btn btn-success">Actualizar Producto</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>';
            }
        }
        
        $contenido .= '</tbody>
        </table>';

// Incluir la plantilla
include 'template.php';
?>
