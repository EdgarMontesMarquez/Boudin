<?php
session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}

// Verificar si se recibió la información del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $texto = $_POST['texto'];
    $id_tema = $_POST['id_tema'];
    $id_usuario = $_SESSION['UserID'];

    // Conexión a la base de datos
    require_once '../../configuraciones/conexion.php'; // Ajusta la ruta según tu estructura

    // Insertar respuesta en la base de datos
    $stmt = $con->prepare("INSERT INTO respuestas_foro (id_tema, id_usuario, respuesta_textual) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id_tema, $id_usuario, $texto);

    if ($stmt->execute()) {
        // Redirigir de vuelta a la página del tema después de insertar la respuesta
        header("Location: ../../vistas/foro/responder_tema.php?id=$id_tema");
        exit();
    } else {
        // Manejar errores de inserción (opcional)
        echo "Error al insertar la respuesta. Por favor, intenta de nuevo.";
    }
} else {
    // Manejar el caso cuando no se reciben datos por POST (opcional)
    header('Location: ../../vistas/foro/viewforo.php'); // Redirigir a la página del foro o manejar el error adecuadamente
    exit();
}
?>
