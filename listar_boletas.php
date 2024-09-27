<?php
// Definir el título y página activa
$titulo = "Ver Boletas Generadas";
$pagina = 'listar_boletas';

// Capturar el contenido de las boletas en el buffer
ob_start();
$archivos = array_diff(scandir('boletas'), ['.', '..']); // Obtiene los archivos en la carpeta
?>
<h2>Boletas Generadas</h2>
<?php
if (count($archivos) > 0) {
    echo "<ul>";
    foreach ($archivos as $archivo) {
        echo "<li><a href='boletas/$archivo' target='_blank'>$archivo</a></li>"; // Enlaza al archivo
    }
    echo "</ul>";
} else {
    echo "<p>No hay boletas generadas.</p>";
}
$contenido = ob_get_clean(); // Almacenar el contenido y limpiar el buffer

// Incluir la plantilla
include 'template.php';
?>