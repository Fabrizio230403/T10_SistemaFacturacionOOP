<?php
require_once 'Persona.php';

class Cliente extends Persona {
    private $direccion;
    private $telefono;

    public function __construct($nombre, $apellido, $dni, $direccion, $telefono) {
        parent::__construct($nombre, $apellido, $dni);
        $this->direccion = $direccion;
        $this->telefono = $telefono;
    }

    public function mostrarInfo() {
        return "Cliente: $this->nombre $this->apellido, DNI: $this->dni, Dirección: $this->direccion, Teléfono: $this->telefono";
    }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
}
?>
