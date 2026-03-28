<?php

namespace App;

use App\ConectionBD\ConectionDB;
use Exception;

/**
 * Clase Database - Wrapper para ConectionDB
 * Compatibilidad con el código existente del login
 */

class Database {
    private static $instance = null;
    private $conn;
    private $dbConnection;
    
    private function __construct() {
        // Cargar configuración si no está cargada
        if (!defined('BASE_URL')) {
            require_once __DIR__ . '/config.php';
        }
        
        // Usar nuestra clase de conexión existente
        $this->dbConnection = new ConectionDB();
        $this->conn = $this->dbConnection->getConnection();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Ejecutar una consulta y obtener un solo resultado
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }
        
        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Ejecutar una consulta y obtener múltiples resultados
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }
        
        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Ejecutar una consulta que no devuelve resultados (INSERT, UPDATE, DELETE)
     */
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conn->error);
        }
        
        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
            $stmt->bind_param($types, ...$params);
        }
        
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $insertId = $this->conn->insert_id;
        $stmt->close();
        
        return [
            'affected_rows' => $affectedRows,
            'insert_id' => $insertId
        ];
    }
    
    /**
     * Obtener el último ID insertado
     */
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
    
    /**
     * Comenzar una transacción
     */
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }
    
    /**
     * Confirmar una transacción
     */
    public function commit() {
        $this->conn->commit();
    }
    
    /**
     * Revertir una transacción
     */
    public function rollback() {
        $this->conn->rollback();
    }
}