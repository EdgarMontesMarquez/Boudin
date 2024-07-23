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
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['UserID'];

    // Validar datos (puedes añadir validaciones adicionales aquí)

    // Conexión a la base de datos
    require_once '../../configuraciones/conexion.php'; // Ajusta la ruta según tu estructura

    // Insertar tema en la base de datos
    $stmt = $con->prepare("INSERT INTO temas (titulo, descripcion, id_usuario) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $titulo, $descripcion, $id_usuario);

    if ($stmt->execute()) {
        // Obtener el ID del tema recién insertado
        $id_tema = $stmt->insert_id;
        
        // Redirigir a la página del tema recién creado
        header("Location: responder_tema.php?id=$id_tema");
        exit();
    } else {
        // Manejar errores de inserción (opcional)
        echo "Error al crear el tema. Por favor, intenta de nuevo.";
    }
} else {
    // Manejar el caso cuando no se reciben datos por POST (opcional)
    header('Location: foro.php'); // Redirigir a la página del foro o manejar el error adecuadamente
    exit();
}
?>
