<?php 
class Producto {
    private $codigo;
    private $nombre;
    private $descripcion;
    private $precioUnitario;
    private $cantidad;

    public function __construct($codigo, $nombre, $descripcion, $precioUnitario, $cantidad) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precioUnitario = $precioUnitario;
        $this->cantidad = $cantidad;
    }

    public function calcularTotal() {
        return $this->precioUnitario * $this->cantidad;
    }

    // Getters y Setters (encapsulamiento)
    public function getCodigo() { return $this->codigo; }
    public function setCodigo($codigo) { $this->codigo = $codigo; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getPrecioUnitario() { return $this->precioUnitario; }
    public function setPrecioUnitario($precioUnitario) { $this->precioUnitario = $precioUnitario; }

    public function getCantidad() { return $this->cantidad; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
}
?>
