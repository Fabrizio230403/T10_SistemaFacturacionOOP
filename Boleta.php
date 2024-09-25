<?php
require_once 'Documento.php';

class Boleta extends Documento {
    public function generarDocumento() {
        $info = "Boleta para: " . $this->mostrarCliente() . "\n";
        $info .= "Productos:\n";
        foreach ($this->productos as $item) {
            $producto = $item['producto'];
            $cantidad = $item['cantidad'];
            $info .= $producto->getNombre() . " - Cantidad: $cantidad - Subtotal: " . number_format($producto->getPrecioUnitario() * $cantidad, 2) . "\n";
        }
        $info .= "Total a pagar: " . number_format($this->total, 2);
        return $info;
    }
}
