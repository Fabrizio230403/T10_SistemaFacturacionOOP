<?php
require_once 'Cliente.php';
require_once 'Producto.php';
require_once 'Documento.php';
require_once 'Boleta.php';
require_once 'Factura.php';

session_start();

// Verifica si hay productos y clientes en la sesión
if (!isset($_SESSION['productos']) || !isset($_SESSION['clientes'])) {
    header('Location: clientes.php'); // Redirige si no hay clientes o productos
    exit;
}

// Inicializa el carrito de compras si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Inicializa el cliente seleccionado
$clienteSeleccionado = null;

// Procesa el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si se busca un cliente
    if (isset($_POST['buscar_cliente'])) {
        $dniBuscar = $_POST['dni_buscar'];
        foreach ($_SESSION['clientes'] as $cliente) {
            if ($cliente->getDni() === $dniBuscar) {
                $clienteSeleccionado = $cliente;
                break;
            }
        }
    }

    // Si se agrega un producto al carrito
    if (isset($_POST['agregar_comprobante'])) {
        $dni = $_POST['dni'];
        $codigoProducto = $_POST['codigo'];
        $cantidad = $_POST['cantidad'];

        // Busca el cliente por DNI
        foreach ($_SESSION['clientes'] as $cliente) {
            if ($cliente->getDni() === $dni) {
                $clienteSeleccionado = $cliente; // Actualiza el cliente seleccionado
                break;
            }
        }

        // Busca el producto por código
        $productoEncontrado = null;
        foreach ($_SESSION['productos'] as $producto) {
            if ($producto->getCodigo() === $codigoProducto) {
                $productoEncontrado = $producto;
                break;
            }
        }

        // Agrega el producto al carrito si el cliente y el producto existen
        if ($clienteSeleccionado && $productoEncontrado) {
            if ($cantidad > 0 && $cantidad <= $productoEncontrado->getCantidad()) {
                // Calcular el precio y subtotal
                $precio = $productoEncontrado->getPrecioUnitario(); // Asumiendo que tienes un método getPrecio()
                $subtotal = $precio * $cantidad;

                $_SESSION['carrito'][] = [
                    'cliente' => $clienteSeleccionado,
                    'producto' => $productoEncontrado,
                    'cantidad' => $cantidad,
                    'codigo' => $productoEncontrado->getCodigo(),
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                ];
                // Reduce la cantidad del producto disponible
                $productoEncontrado->setCantidad($productoEncontrado->getCantidad() - $cantidad);
            } else {
                echo "Cantidad inválida.";
            }
        } else {
            echo "Cliente o producto no encontrado.";
        }
    }

    // Si se genera el comprobante
    if (isset($_POST['generar_comprobante'])) {
        $clienteSeleccionado = isset($_SESSION['clienteSeleccionado']) ? $_SESSION['clienteSeleccionado'] : null;

        $tipoComprobante = $_POST['tipo_comprobante'];

        if ($clienteSeleccionado) {
            // Crea el documento según el tipo seleccionado
            $documento = ($tipoComprobante === 'factura') ? new Factura($clienteSeleccionado) : new Boleta($clienteSeleccionado);

            // Agrega los productos al documento
            foreach ($_SESSION['carrito'] as $item) {
                $documento->agregarProducto($item['producto'], $item['cantidad']);
            }

            // Muestra el documento
            echo nl2br($documento->generarDocumento());
            $_SESSION['carrito'] = [];
            $_SESSION['clienteSeleccionado'] = null;

            echo '<br><a href="generarcomprobante.php">Generar Nuevo Comprobante</a>';
            exit; 
        } else {
            echo "Cliente no encontrado.";
        }
    }

    // Si se limpia el carrito
    if (isset($_POST['limpiar_carrito'])) {
        $_SESSION['carrito'] = []; 
    }
}

// Almacena el cliente seleccionado en la sesión
if ($clienteSeleccionado) {
    $_SESSION['clienteSeleccionado'] = $clienteSeleccionado;
} else {
    $clienteSeleccionado = isset($_SESSION['clienteSeleccionado']) ? $_SESSION['clienteSeleccionado'] : null;
}

// Título de la página
$titulo = "Generar Comprobante";

// Contenido específico de la página
$contenido = '
    <h2 class="mt-4">Buscar Cliente</h2>
    <form method="POST" class="form-inline mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="dni_buscar" placeholder="DNI del Cliente" required>
            <div class="input-group-append">
                <button type="submit" name="buscar_cliente" class="btn btn-primary">Buscar Cliente</button>
            </div>
        </div>
    </form>

    <h2>Datos del Cliente</h2>
    ' . ($clienteSeleccionado ? '
        <div class="card mb-3">
            <div class="card-body">
                <p><strong>DNI:</strong> ' . $clienteSeleccionado->getDni() . '</p>
                <p><strong>Nombre:</strong> ' . $clienteSeleccionado->getNombre() . '</p>
                <p><strong>Apellido:</strong> ' . $clienteSeleccionado->getApellido() . '</p>
                <p><strong>Dirección:</strong> ' . $clienteSeleccionado->getDireccion() . '</p>
                <p><strong>Teléfono:</strong> ' . $clienteSeleccionado->getTelefono() . '</p>
            </div>
        </div>
    ' : '<p>No se ha seleccionado ningún cliente.</p>') . '

    <h2 class="mt-4">Agregar Productos</h2>
    <form method="POST" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" class="form-control" name="codigo" placeholder="Código del Producto" required>
        </div>
        <div class="form-group mr-2">
            <input type="number" class="form-control" name="cantidad" placeholder="Cantidad" required>
        </div>
        <input type="hidden" name="dni" value="' . ($clienteSeleccionado ? $clienteSeleccionado->getDni() : '') . '">
        <button type="submit" name="agregar_comprobante" class="btn btn-success">Agregar Producto</button>
    </form>

    <h2 class="mt-4">Carrito de Compras</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            ' . (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0 ? implode('', array_map(function($item) {
                return '<tr>
                    <td>' . $item['codigo'] . '</td>
                    <td>' . $item['producto']->getNombre() . '</td>
                    <td>' . $item['cantidad'] . '</td>
                    <td>' . $item['precio'] . '</td>
                    <td>' . $item['subtotal'] . '</td>
                </tr>';
            }, $_SESSION['carrito'])) : '<tr><td colspan="5">No hay productos en el carrito.</td></tr>') . '
        </tbody>
    </table>
    <form method="POST">
        <button type="submit" name="limpiar_carrito" class="btn btn-danger">Limpiar Carrito</button>
    </form>

    <h2 class="mt-4">Generar Comprobante</h2>
    <form method="POST" class="form-inline mb-3">
        <div class="form-group mr-2">
            <select name="tipo_comprobante" class="form-control" required>
                <option value="boleta">Boleta</option>
                <option value="factura">Factura</option>
            </select>
        </div>
        <button type="submit" name="generar_comprobante" class="btn btn-primary">Generar Comprobante</button>
    </form>
';

// Incluye la plantilla
include 'template.php';
