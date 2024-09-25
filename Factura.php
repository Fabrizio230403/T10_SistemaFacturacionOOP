<?php
require_once 'Documento.php';

class Factura extends Documento {
    private $igv;

    public function __construct($cliente) {
        parent::__construct($cliente);
        $this->igv = 0.18; // IGV del 18%
    }

    public function generarDocumento() {
        $info = "Factura para: " . $this->mostrarCliente() . "\n";
        $info .= "Productos:\n";
        foreach ($this->productos as $item) {
            $producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $info .= $producto->getNombre() . " - Cantidad: $cantidad - Subtotal: " . number_format($producto->getPrecioUnitario() * $cantidad, 2) . "\n";
        }
        $totalConIgv = $this->total + ($this->total * $this->igv);
        $info .= "Total sin IGV: " . number_format($this->total, 2) . "\n";
        $info .= "Total con IGV: " . number_format($totalConIgv, 2);
        return $info;
    }
}
