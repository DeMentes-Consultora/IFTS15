<?php
namespace App\Model;

/**
 * Modelo de Consulta - IFTS15
 * Archivo: src/Model/Consulta.php
 */

class Consulta {
    private $nombre;
    private $email;
    private $telefono;
    private $carrera;
    private $mensaje;
    
    public function __construct($nombre, $email, $mensaje, $telefono = '', $carrera = '') {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->mensaje = $mensaje;
        $this->telefono = $telefono;
        $this->carrera = $carrera;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getCarrera() {
        return $this->carrera;
    }
    
    public function getMensaje() {
        return $this->mensaje;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    
    public function setCarrera($carrera) {
        $this->carrera = $carrera;
    }
    
    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }
}