<?php
require_once '../../configuraciones/conexion.php';

// Verificar si se recibió el ID de la actividad
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Realizar la eliminación de respuestas asociadas a la actividad
    $sqlEliminarRespuestas = "DELETE FROM respuestas WHERE id_actividad = $id";
    $resultadoEliminarRespuestas = mysqli_query($con, $sqlEliminarRespuestas);

    // Verificar si la eliminación de respuestas fue exitosa
    if (!$resultadoEliminarRespuestas) {
        echo "Error al eliminar las respuestas asociadas a la actividad.";
        mysqli_close($con);
        exit();
    }

    // Realizar la consulta DELETE en la base de datos para la actividad
    $sqlEliminarActividad = "DELETE FROM actividades WHERE id = $id";
    $resultadoEliminarActividad = mysqli_query($con, $sqlEliminarActividad);

    // Verificar si la eliminación de la actividad fue exitosa
    if ($resultadoEliminarActividad) {
        header("Location: ../../vistas/administrador/Actividades.php");
        exit();
    } else {
        echo "Error al eliminar la actividad.";
    }
} else {
    echo "No se recibió el ID de la actividad";
}

mysqli_close($con);
?>
