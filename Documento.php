<?php
require_once 'Cliente.php';
require_once 'Producto.php';

abstract class Documento {
    protected $cliente;
    protected $productos = [];
    protected $total;

    public function __construct($cliente) {
        $this->cliente = $cliente;
        $this->total = 0;
    }

    public function agregarProducto(Producto $producto, $cantidad) {
        $this->productos[] = ['producto' => $producto, 'cantidad' => $cantidad];
        $this->total += $producto->getPrecioUnitario() * $cantidad;
    }

    public function getTotal() {
        return $this->total;
    }

    public function mostrarCliente() {
        return $this->cliente->getNombre() . ' ' . $this->cliente->getApellido();
    }

    abstract public function generarDocumento();
}