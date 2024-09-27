<?php
require_once 'Documento.php';

class Factura extends Documento {
    private $igv;

    public function __construct($cliente) {
        parent::__construct($cliente);
        $this->igv = 0.18; // IGV del 18%
    }

    public function generarDocumento() {
        // Crea una nueva instancia de FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        // Habilitar UTF-8 para caracteres especiales
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTitle(utf8_decode('Factura de Venta'));

        // Encabezado de la factura
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('FACTURA DE VENTA'), 0, 1, 'C');
        $pdf->Ln(5);

        // Información del cliente
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Fecha de emisión: ') . date('d/m/Y'), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Cliente: ') . utf8_decode($this->mostrarCliente()), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Dirección: ') . utf8_decode($this->cliente->getDireccion()), 0, 1);
        $pdf->Cell(0, 10, utf8_decode('Teléfono: ') . $this->cliente->getTelefono(), 0, 1);
        $pdf->Ln(10);

        // Configuración de colores para la tabla
        $pdf->SetFillColor(230, 230, 230); // Color de fondo para los encabezados
        $pdf->SetTextColor(0); // Color de texto
        $pdf->SetDrawColor(128, 128, 128); // Color de bordes

        // Encabezados de la tabla de productos
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, utf8_decode('Código'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(50, 10, utf8_decode('Descripción'), 1, 0, 'C', true);
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
            $pdf->Cell(60, 10, utf8_decode($producto->getNombre()), 1);
            $pdf->Cell(50, 10, utf8_decode($producto->getDescripcion()), 1);
            $pdf->Cell(25, 10, number_format($producto->getPrecioUnitario(), 2), 1, 0, 'R');
            $pdf->Cell(15, 10, $cantidad, 1, 0, 'C');
            $pdf->Cell(25, 10, $subtotal, 1, 1, 'R');
        }

        // Calcular el total con y sin IGV
        $totalSinIgv = $this->total;
        $igvCalculado = $this->total * $this->igv;
        $totalConIgv = $totalSinIgv + $igvCalculado;

        // Líneas de totales
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(170, 10, 'TOTAL sin IGV: ', 0, 0, 'R');
        $pdf->Cell(25, 10, number_format($totalSinIgv, 2), 1, 1, 'R');

        $pdf->Cell(170, 10, 'IGV (18%): ', 0, 0, 'R');
        $pdf->Cell(25, 10, number_format($igvCalculado, 2), 1, 1, 'R');

        $pdf->Cell(170, 10, 'TOTAL con IGV: ', 0, 0, 'R');
        $pdf->Cell(25, 10, number_format($totalConIgv, 2), 1, 1, 'R');

        // Guardar el documento en un archivo PDF
        $nombreArchivo = 'factura_' . date('Ymd_His') . '.pdf';
        $pdf->Output('F', 'facturas/' . $nombreArchivo);

        // Devuelve el nombre del archivo
        return $nombreArchivo; 
    }
}
