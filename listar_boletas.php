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
        echo "<li class='factura-item d-flex justify-content-between align-items-center'>
                <div>
                    <img src='imagenes/logo_pdf.png' alt='PDF' class='factura-icon'>
                    <a href='boletas/$archivo' target='_blank' class='mr-3'>$archivo</a>
                </div>
                <button class='btn btn-primary btn-enviar-reporte' onclick='openModal(\"$archivo\", \"boleta\")'>Enviar Reporte</button>

                
            </li>";
     }
    echo "</ul>";
} else {
    echo "<p>No hay boletas generadas.</p>";
}
$contenido = ob_get_clean(); // Almacenar el contenido y limpiar el buffer

 
include 'template.php';
?>

 <!-- Modal -->
 <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Enviar Reporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="CLCorreo.php" method="POST">
                    <input type="hidden" name="archivo" id="archivo" value="">
                    <input type="hidden" name="tipo" id="tipo" value="">
                    <div class="form-group">
                        <label for="nombre">Datos del Emisor:</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono de Contacto:</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico del Destinatario:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="asunto">Asunto del Mensaje:</label>
                        <input type="text" class="form-control" name="asunto" required>
                    </div>
                    <div class="form-group" style="text-align: right;">
                        <button type="submit" class="btn btn-primary"
                         style="background-color: #007bff; height: 38px; padding: 0 20px;">Enviar Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
<script>
function openModal(archivo, tipo) {
    document.getElementById('archivo').value = archivo;  
    document.getElementById('tipo').value = tipo;  
    $('#modal').modal('show');  
}
</script>