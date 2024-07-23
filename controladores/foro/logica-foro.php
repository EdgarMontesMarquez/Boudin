<?php

session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}

// Conexión a la base de datos
require_once '../../configuraciones/conexion.php';

// Inicializar variables para la búsqueda
$searchTerm = '';
$whereClause = '';

// Procesar el término de búsqueda si se envía
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $whereClause = "WHERE temas.titulo LIKE '%$searchTerm%' OR temas.descripcion LIKE '%$searchTerm%'";
}

// Consulta para obtener los temas del foro con sus respuestas
$stmtTemas = $con->prepare("SELECT temas.id, temas.titulo, temas.descripcion, temas.fecha_creacion, usuarios.NombreUsuario AS NombreCreador, COUNT(respuestas_foro.id) AS num_respuestas
                            FROM temas
                            LEFT JOIN respuestas_foro ON temas.id = respuestas_foro.id_tema
                            JOIN usuarios ON temas.id_usuario = usuarios.UserID
                            $whereClause
                            GROUP BY temas.id
                            ORDER BY temas.fecha_creacion DESC");
$stmtTemas->execute();
$resultadoTemas = $stmtTemas->get_result();

$temas = $resultadoTemas->fetch_all(MYSQLI_ASSOC);

?>