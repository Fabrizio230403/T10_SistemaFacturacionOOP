 
 <?php
require_once 'Cliente.php';
session_start();

// Inicializar variables para el formulario
$nombre = $apellido = $dni = $direccion = $telefono = '';
$modoEdicion = false;

// Cargar datos del producto para editar
if (isset($_GET['editar'])) {
    $indiceEdicion = $_GET['editar'];
    $cliente = $_SESSION['clientes'][$indiceEdicion];
    $nombre = $cliente->getNombre();
    $apellido = $cliente->getApellido();
    $dni = $cliente->getDni();
    $direccion = $cliente->getDireccion();
    $telefono = $cliente->getTelefono();
    $modoEdicion = true;
}

// Función para actualizar el producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_cliente'])) {
    $indiceEdicion = $_POST['indice'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Actualizar el producto en la sesión
    $_SESSION['clientes'][$indiceEdicion] = new Cliente($nombre, $apellido, $dni, $direccion, $telefono);
    
    // Redireccionar a la página principal después de la actualización
    header('Location: clientes.php');
    exit();
}

// Variables para la plantilla
$titulo = "Editar Cliente";
$contenido = '
<h2>Actualizar Cliente</h2>
<form method="POST">
    <input type="hidden" name="indice" value="' . htmlspecialchars($indiceEdicion) . '">
    <label>Nombre: <input type="text" name="nombre" value="' . htmlspecialchars($nombre) . '" required></label><br>
    <label>Apellido: <input type="text" name="apellido" value="' . htmlspecialchars($apellido) . '" required></label><br>
    <label>DNI: <input type="text" name="dni" value="' . htmlspecialchars($dni) . '" required></label><br>
    <label>Dirección: <input type="text" name="direccion" value="' . htmlspecialchars($direccion) . '" required></label><br>
    <label>Teléfono: <input type="text" name="telefono" value="' . htmlspecialchars($telefono) . '" required></label><br>
    <button type="submit" name="actualizar_cliente">Actualizar Cliente</button>
</form>
';

// Incluir la plantilla
include 'template.php';
?>

