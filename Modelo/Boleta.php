<?php
require_once 'Documento.php';
 
class Boleta extends Documento {
    public function generarDocumento() {
        // Crea una nueva instancia de FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        // Habilitar UTF-8 para caracteres especiales
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTitle(utf8_decode('Boleta de Venta'));

        // Encabezado de la boleta
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('BOLETA DE VENTA'), 0, 1, 'C');
        $pdf->Ln(5);

        // Información del cliente
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Fecha de emisión: ') . date('d/m/Y'), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Cliente: ') . utf8_decode($this->mostrarCliente()), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Dirección: ') . utf8_decode($this->cliente->getDireccion()), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Teléfono: ') . $this->cliente->getTelefono(), 0, 1);
        $pdf->Ln(10);

        // Configuración de colores para la tabla
        $pdf->SetFillColor(230, 230, 230); 
        $pdf->SetTextColor(0);  
        $pdf->SetDrawColor(128, 128, 128);  

        // Encabezados de la tabla de productos
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, utf8_decode('Código'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, 'Precio', 1, 0, 'C', true);
        $pdf->Cell(15, 10, 'Cant.', 1, 0, 'C', true);
        $pdf->Cell(25, 10, 'Subtotal', 1, 1, 'C', true);

        // Información de los productos
        $pdf->SetFont('Arial', '', 12);
        foreach ($this->productos as $item) {
            $producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $subtotal = number_format($producto->getPrecioUnitario() * $cantidad, 2);

            $pdf->Cell(20, 10, $producto->getCodigo(), 1);
            $pdf->Cell(40, 10, utf8_decode($producto->getNombre()), 1);
            $pdf->Cell(70, 10, utf8_decode($producto->getDescripcion()), 1);
            $pdf->Cell(25, 10, number_format($producto->getPrecioUnitario(), 2), 1, 0, 'R');
            $pdf->Cell(15, 10, $cantidad, 1, 0, 'C');
            $pdf->Cell(25, 10, $subtotal, 1, 1, 'R');
        }

        // Total final
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(170, 10, 'TOTAL: ', 0, 0, 'R');
        $pdf->Cell(25, 10, number_format($this->total, 2), 1, 1, 'R');

        // Guardar en archivo PDF
        $nombreArchivo = 'boleta_' . date('Ymd_His') . '.pdf';
        $pdf->Output('F', 'boletas/' . $nombreArchivo);

        return $nombreArchivo; 
    }
}
 