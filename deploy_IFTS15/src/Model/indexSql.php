<?php
/**
 * Consultas SQL para estadísticas del index
 * 
 * Constantes con queries predefinidas para obtener contadores
 * de entidades activas en el sistema
 * 
 * @package App\Model
 */

// Cantidad de alumnos activos (rol 1, habilitados y no cancelados)
const SQL_CANTIDAD_ALUMNOS = "SELECT COUNT(*) as total FROM usuario WHERE id_rol = 1 AND habilitado = 1 AND cancelado = 0";

// Cantidad de profesores activos (rol 2, habilitados y no cancelados)
const SQL_CANTIDAD_PROFESORES = "SELECT COUNT(*) as total FROM usuario WHERE id_rol = 2 AND habilitado = 1 AND cancelado = 0";

// Cantidad de carreras activas (habilitadas y no canceladas)
const SQL_CANTIDAD_CARRERAS = "SELECT COUNT(*) as total FROM carrera WHERE habilitado = 1 AND cancelado = 0";
