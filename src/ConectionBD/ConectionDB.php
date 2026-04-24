<?php

namespace App\ConectionBD;

use mysqli;
use Exception;

/**
 * Clase de Conexión a Base de Datos - IFTS15
 * Basada en el patrón de La Canchita de Los Pibes con phpdotenv
 * 
 * @package App\ConectionBD
 * @author IFTS15 Team
 * @version 2.0
 */

class ConectionDB
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $port;
    private $conn;
    private $charset;

    public function __construct()
    {
        // Cargar configuración si no está cargada
        if (!function_exists('env')) {
            require_once __DIR__ . '/../config.php';
        }
        
        // Cargar configuración desde variables de entorno usando la función helper
        $this->host     = env('DB_HOST', 'localhost');
        $this->username = env('DB_USERNAME', 'root');
        $this->password = env('DB_PASSWORD', '');
        $this->dbname   = env('DB_NAME', 'ifts15');
        $this->port     = (int) env('DB_PORT', 3306);
        $this->charset  = env('DB_CHARSET', 'utf8mb4');

        // Crear conexión MySQLi
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname,
            $this->port
        );

        // Verificar conexión
        if ($this->conn->connect_error) {
            error_log("Error de conexión BD: " . $this->conn->connect_error);
            throw new Exception("Error de conexión a la base de datos");
        }

        // Configurar charset
        $this->conn->set_charset($this->charset);
    }

    /**
     * Obtiene la conexión MySQLi
     * 
     * @return mysqli Conexión a la base de datos
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Cierra la conexión a la base de datos
     */
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Obtener listado de carreras activas
     * 
    * @return array Array de carreras con id_carrera y carrera
     */
    public function getCarreras()
    {
        $carreras = [];
        $query = "SELECT id_carrera, nombreCarrera AS carrera FROM carrera WHERE habilitado = 1 AND cancelado = 0 ORDER BY nombreCarrera";
        
        $result = $this->conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $carreras[] = $row;
            }
        }
        
        return $carreras;
    }

    /**
     * Obtener todas las comisiones habilitadas
     * 
     * @return array Array de comisiones con id_comision y comision
     */
    public function getComisiones()
    {
        $comisiones = [];
        $query = "SELECT id_comision, comision FROM comision WHERE habilitado = 1 AND cancelado = 0 ORDER BY comision";
        
        $result = $this->conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $comisiones[] = $row;
            }
        }
        
        return $comisiones;
    }

    /**
     * Obtener todos los años cursada habilitados
     * 
     * @return array Array de años con id_añoCursada y año
     */
    public function getAñosCursada()
    {
        $años = [];
        $query = "SELECT id_añoCursada, año FROM añocursada WHERE habilitado = 1 AND cancelado = 0 ORDER BY año";
        
        $result = $this->conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $años[] = $row;
            }
        }
        
        return $años;
    }

    // Eliminado destructor automático para evitar cierre prematuro de la conexión
}