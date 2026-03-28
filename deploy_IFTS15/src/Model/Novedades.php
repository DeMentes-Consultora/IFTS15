<?php
namespace App\Model;

// Modelo Novedades: POO, MVC
class Novedades {
	private $id_novedades;
	private $novedad;
	private $idCreate;
	private $idUpdate;
	private $habilitado;
	private $cancelado;

	public function __construct($novedad = null) {
		$this->novedad = $novedad;
	}

	// Getters
	public function getId() { return $this->id_novedades; }
	public function getNovedad() { return $this->novedad; }
	public function getFecha() { return $this->idCreate; }
	public function getUpdate() { return $this->idUpdate; }
	public function getHabilitado() { return $this->habilitado; }
	public function getCancelado() { return $this->cancelado; }

	// Setters
	public function setNovedad($novedad) { $this->novedad = $novedad; }

	// Insertar novedad
	public static function insertar($conn, $novedad) {
		$sql = "INSERT INTO novedades (novedad) VALUES (?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $novedad);
		return $stmt->execute();
	}

	// Listar novedades (orden descendente)
	public static function listar($conn) {
		$sql = "SELECT id_novedades, novedad, idCreate FROM novedades WHERE habilitado=1 AND cancelado=0 ORDER BY id_novedades DESC";
		$result = $conn->query($sql);
		$novedades = [];
		while ($row = $result->fetch_assoc()) {
			$novedades[] = $row;
		}
		return $novedades;
	}
}
