<?php
// Definir el título y página activa
$titulo = "Ver Facturas Generadas";
$pagina = 'listar_facturas';

// Capturar el contenido de las facturas en el buffer
ob_start();
$archivos = array_diff(scandir('facturas'), ['.', '..']); // Obtiene los archivos en la carpeta
?>
<h2>Facturas Generadas</h2>
<?php
if (count($archivos) > 0) {
    echo "<ul>";
    foreach ($archivos as $archivo) {
        echo "<li><a href='facturas/$archivo' target='_blank'>$archivo</a></li>"; // Enlaza al archivo
    }
    echo "</ul>";
} else {
    echo "<p>No hay facturas generadas.</p>";
}
$contenido = ob_get_clean(); // Almacenar el contenido y limpiar el buffer

// Incluir la plantilla
include 'template.php';
?>
