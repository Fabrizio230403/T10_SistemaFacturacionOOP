<?php
setlocale(LC_TIME, 'spanish');

$titulo = "Ver Boletas Generadas";
$pagina = 'listar_boletas';

// Capturar el contenido de las boletas en el buffer
ob_start();
$archivos = array_diff(scandir('boletas'), ['.', '..']); // Obtener los archivos en la carpeta
?>
<h3 class="mb-4">Boletas Generadas</h3>
<?php
if (count($archivos) > 0) {
    echo "<div class='facturas-container'>";
    echo "<h5 class='factura-header' onclick='toggleFacturas(this)'>Boleta del mes de " . strftime('%B %Y') . " <span class='arrow'>&#9660;</span></h5>";
    echo "<ul class='factura-list' style='display: none;'>";
    foreach ($archivos as $archivo) {
        echo "<li class='factura-item'><img src='imagenes/logo_pdf.png' alt='PDF' class='factura-icon'><a href='boletas/$archivo' target='_blank'>$archivo</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>No hay boletas generadas.</p>";
}
$contenido = ob_get_clean(); // Almacenar el contenido y limpiar el buffer

 
include 'template.php';
?>
