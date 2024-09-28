<?php
setlocale(LC_TIME, 'spanish');  
 
$titulo = "Ver Facturas Generadas";
$pagina = 'listar_facturas';

 
ob_start();
$archivos = array_diff(scandir('facturas'), ['.', '..']); 
?>
<h3 class="mb-4">Facturas Generadas</h3>
<?php
if (count($archivos) > 0) {
    
    echo "<div class='facturas-container'>";
    echo "<h5 class='factura-header' onclick='toggleFacturas(this)'>Factura del mes de " . strftime('%B %Y') . " <span class='arrow'>&#9660;</span></h5>";
    echo "<ul class='factura-list' style='display: none;'>";  
    foreach ($archivos as $archivo) {
        echo "<li class='factura-item'><img src='imagenes/logo_pdf.png' alt='PDF' class='factura-icon'><a href='facturas/$archivo' target='_blank'>$archivo</a></li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<p>No hay facturas generadas.</p>";
}
$contenido = ob_get_clean(); 

 
include 'template.php';
?>
