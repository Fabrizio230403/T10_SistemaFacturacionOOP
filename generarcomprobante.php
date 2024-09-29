<?php
 require_once 'Modelo/Cliente.php';
require_once 'Modelo/Producto.php';
require_once 'Modelo/Documento.php';
require_once 'Modelo/Boleta.php';
require_once 'Modelo/Factura.php';

session_start();

// Inicializar mensaje de éxito para el modal
$mensajeExito = '';  

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

    if (isset($_POST['buscar_producto'])) {
        $codigoBuscar = $_POST['codigo_producto_buscar'];
        $productoSeleccionado = null; // Inicializa en null cada vez que se busca
        foreach ($_SESSION['productos'] as $producto) {
            if ($producto->getCodigo() === $codigoBuscar) {
                $productoSeleccionado = $producto;
                break;
            }
        }
    }

    if (isset($_POST['eliminar_producto'])) {
        $indice = $_POST['indice_eliminar'];
        // Remueve el producto del carrito en la posición especificada
        array_splice($_SESSION['carrito'], $indice, 1);
        
    }

    // Manejamos el aumento de la cantidad
if (isset($_POST['aumentar_cantidad'])) {
    $indice = $_POST['indice_actualizar'];
    
    // Obtener el producto actual del carrito
    $producto = $_SESSION['carrito'][$indice]['producto'];
    
    // Verificar si la cantidad actual es menor que el stock del producto
    if ($_SESSION['carrito'][$indice]['cantidad'] < $producto->getCantidad()) { // Añadir 'getCantidad()' de mi clase Producto
        $_SESSION['carrito'][$indice]['cantidad']++; // Aumentar la cantidad
        $precio = $producto->getPrecioUnitario();
        $_SESSION['carrito'][$indice]['subtotal'] = $precio * $_SESSION['carrito'][$indice]['cantidad']; // Recalcular el subtotal
    } else {
        echo "No puedes aumentar más la cantidad. Stock máximo alcanzado.";
    }
}

     // Manejamos la disminución de la cantidad
     if (isset($_POST['disminuir_cantidad'])) {
        $indice = $_POST['indice_actualizar'];
        if ($_SESSION['carrito'][$indice]['cantidad'] > 1) {
            $_SESSION['carrito'][$indice]['cantidad']--; // Disminuimos la cantidad si es mayor a 1
            $producto = $_SESSION['carrito'][$indice]['producto'];
            $precio = $producto->getPrecioUnitario();
            $_SESSION['carrito'][$indice]['subtotal'] = $precio * $_SESSION['carrito'][$indice]['cantidad']; // Recalcular el subtotal
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
        // Verifica que la cantidad sea válida
        if ($cantidad > 0 && $cantidad <= $productoEncontrado->getCantidad()) {
            // Verifica si el producto ya existe en el carrito
            $productoYaEnCarrito = false;
            foreach ($_SESSION['carrito'] as &$item) {
                if ($item['codigo'] === $productoEncontrado->getCodigo()) {
                    // Aumenta la cantidad y actualiza el subtotal
                    $item['cantidad'] += $cantidad;
                    // Verifica que la cantidad total no exceda la cantidad disponible
                    if ($item['cantidad'] > $productoEncontrado->getCantidad()) {
                        echo "No se puede agregar más productos de los que hay en stock.";
                        $item['cantidad'] -= $cantidad; // Revierte la cantidad
                    } else {
                        $precio = $item['producto']->getPrecioUnitario();
                        $item['subtotal'] = $precio * $item['cantidad'];
                    }
                    $productoYaEnCarrito = true;
                    break;
                }
            }

            // Si el producto no estaba en el carrito, agregarlo
            if (!$productoYaEnCarrito) {
                // Calcular el precio y subtotal
                $precio = $productoEncontrado->getPrecioUnitario(); // Añadir el método getPrecioUnitario() de mi clase Producto
                $subtotal = $precio * $cantidad;

                $_SESSION['carrito'][] = [
                    'cliente' => $clienteSeleccionado,
                    'producto' => $productoEncontrado,
                    'cantidad' => $cantidad,
                    'codigo' => $productoEncontrado->getCodigo(),
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                ];
            }
        } else {
            echo "Cantidad inválida o excede el stock disponible.";
        }
    } else {
        echo "Cliente o producto no encontrado.";
    }
    }


    // Si se genera el comprobante
    if (isset($_POST['generar_comprobante'])) {
        $clienteSeleccionado = isset($_SESSION['clienteSeleccionado']) ? $_SESSION['clienteSeleccionado'] : null;
        $tipoComprobante = $_POST['tipo_comprobante'];

        if(empty($_SESSION['carrito'])) {
            echo "El carrito esta vacío. No puedes generar un comprobante sin productos.";     
        } else { 

            if ($clienteSeleccionado) {
                // Crea el documento según el tipo de comprobante que elija
                $documento = ($tipoComprobante === 'factura') ? new Factura($clienteSeleccionado) : new Boleta($clienteSeleccionado);

                // Agrega los productos al documento
                foreach ($_SESSION['carrito'] as $item) {
                    $documento->agregarProducto($item['producto'], $item['cantidad']);
                    
                    // Aca reducimos el stock del producto cuando se genera el comprobante
                    $productoEncontrado = $item['producto'];
                    $nuevoStock = $productoEncontrado->getCantidad() - $item['cantidad'];
                    if ($nuevoStock < 0) {
                        echo "Error: stock insuficiente para el producto " . $productoEncontrado->getNombre();
                    } else {
                        $productoEncontrado->setCantidad($nuevoStock); // Actualiza la cantidad del producto
                    }
                }
    
                // Generar el documento en PDF y guardarlo
                $nombreArchivo = $documento->generarDocumento();

                // Establecer mensaje de éxito según el tipo de comprobante
                $tipoComprobanteTexto = ($tipoComprobante === 'factura') ? 'Factura' : 'Boleta';
                $mensajeExito = "$tipoComprobanteTexto generado con éxito.";

                
                $_SESSION['carrito'] = [];
                $_SESSION['clienteSeleccionado'] = null;
                // Reiniciar el cliente seleccionado a null
                $clienteSeleccionado = null; 
                
            } else {
                echo "Cliente no encontrado.";
            }
        }
    } 

    // Limpieza del carrito
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

 
$titulo = "Generar Comprobante";

$pagina = "comprobantes";  

 
$contenido = '
<div class="row">
    <div class="col-md-6"> <!-- Columna izquierda para buscar cliente y mostrar datos -->
    <h3 class="mb-3 mt-2">Buscar Cliente</h3>
    <form method="POST" class="form-inline mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="dni_buscar" placeholder="DNI del Cliente" required>
            <div class="input-group-append">
                <button type="submit" name="buscar_cliente" class="btn btn-primary">Buscar Cliente</button>
            </div>
        </div>
    </form>

   
    ' . ($clienteSeleccionado ? '
        <div class="card col-md-10 mb-3 mt-3">
            <div class="card-body">
                <h5 class="text-center"><strong>Datos del Cliente</strong></h5><br>
                <p><strong>DNI:</strong> ' . $clienteSeleccionado->getDni() . '</p>
                <p><strong>Nombre:</strong> ' . $clienteSeleccionado->getNombre() . '</p>
                <p><strong>Apellido:</strong> ' . $clienteSeleccionado->getApellido() . '</p>
                <p><strong>Dirección:</strong> ' . $clienteSeleccionado->getDireccion() . '</p>
                <p><strong>Teléfono:</strong> ' . $clienteSeleccionado->getTelefono() . '</p>
            </div>
        </div>
    ' : '<p>No se ha seleccionado ningún cliente.</p>') . '
    </div>

    <div class="col-md-6">
    <h3 class="mb-3 mt-2">Buscar Producto</h3>
     <form method="POST" class="form-inline mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="codigo_producto_buscar" placeholder="Código del Producto" required>
            <div class="input-group-append">
                <button type="submit" name="buscar_producto" class="btn btn-primary">Buscar Producto</button>
            </div>
        </div>
    </form>
    
       
        ' . (isset($productoSeleccionado) ? '
        <div class="card col-md-10 mb-3 mt-3">
            <div class="card-body">
              <h5 class="text-center"><strong>Datos del Producto</strong></h5><br>
                <p><strong>Código:</strong> ' . $productoSeleccionado->getCodigo() . '</p>
                <p><strong>Nombre:</strong> ' . $productoSeleccionado->getNombre() . '</p>
                <p><strong>Descripción:</strong> ' . $productoSeleccionado->getDescripcion() . '</p>
                <p><strong>Precio:</strong> S/. ' . number_format($productoSeleccionado->getPrecioUnitario(), 2) . '</p>
                <p><strong>Cantidad:</strong> ' . $productoSeleccionado->getCantidad() . '</p>
            </div>
        </div>
        ' : '') . '
     
    <h3 class="mb-3 mt-4">Agregar Producto</h3>
      <form method="POST" class="form-inline mb-3">
        <div class="form-group mr-2">
            <input type="text" class="form-control" name="codigo" placeholder="Código del Producto" required>
        </div>
        <div class="form-group mr-2">
            <input type="number" class="form-control" name="cantidad" placeholder="Cantidad" required value="1" min="1">
        </div>
        <input type="hidden" name="dni" value="' . ($clienteSeleccionado ? $clienteSeleccionado->getDni() : '') . '">
        <button type="submit" name="agregar_comprobante" class="btn btn-success">Agregar Producto</button>
    </form>

    </div>
</div>

    <h3 class="mt-4">Carrito de Compras</h3>
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
            ' . (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0 ? implode('', array_map(function($item,$index) {
                return '<tr>
                    <td>' . $item['codigo'] . '</td>
                    <td>' . $item['producto']->getNombre() . '</td>
                   <td>    
                          <form method="POST" style="display:inline;">
                            <input type="hidden" name="indice_actualizar" value="' . $index . '">
                            <button type="submit" name="disminuir_cantidad" class="btn btn-secondary" style="margin-right: 10px;">-</button>
                         ' . $item['cantidad'] . '
                            <input type="hidden" name="indice_actualizar" value="' . $index . '">
                            <button type="submit" name="aumentar_cantidad" class="btn btn-secondary"  style="margin-left: 10px;">+</button>
                        </form>
                    </td>
                    <td>' . $item['precio'] . '</td>
                    <td>' . $item['subtotal'] . '</td>
                     <td>
                     
                         </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="indice_eliminar" value="' . $index . '">
                            <button type="submit" name="eliminar_producto" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>';
              }, $_SESSION['carrito'], array_keys($_SESSION['carrito']))) : '<tr><td colspan="6">No hay productos en el carrito.</td></tr>') . '
        </tbody>
    </table>
    <form method="POST">
        <button type="submit" name="limpiar_carrito" class="btn btn-danger">Limpiar Carrito</button>
    </form>
    <h3 class="mt-4">Generar Comprobante</h3>
    <form method="POST" class="form-inline mb-3">
        <div class="form-group mr-2">
            <select name="tipo_comprobante" class="form-control" required>
                <option value="">Elige el tipo de comprobante</option>
                <option value="boleta">Boleta</option>
                <option value="factura">Factura</option>
            </select>
        </div>
        <button type="submit" name="generar_comprobante" class="btn btn-primary">Generar Comprobante</button>
    </form>
';

 
include 'template.php';

  //  Script para mostrar el modal
  if ($mensajeExito) {
    // Determina si el mensaje de éxito incluye "Factura" o "Boleta"
    $tipoComprobante = strpos($mensajeExito, 'Factura') !== false ? 'Factura' : 'Boleta';
    echo '<script>
        swal({
            title: "Éxito!",
            text: "' . $mensajeExito . '",
            type: "success",
            buttons: {
                confirm: {
                    text: "Cerrar",
                    className: "btn-primary",
                }
            },
            confirmButtonColor: "#007bff" // Cambia el color del botón a azul
        });
    </script>';
}
 

 
