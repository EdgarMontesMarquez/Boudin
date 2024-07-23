<?php
session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boudin";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $userId = $_SESSION['UserID']; // Suponiendo que el ID del usuario está almacenado en la sesión

    // Validar datos
    if (empty($titulo) || empty($descripcion)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Insertar en la base de datos
    $sql = "INSERT INTO temas (titulo, descripcion, id_usuario) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssi", $titulo, $descripcion, $userId);
        if ($stmt->execute()) {
            // Redirigir al foro principal o a la página del nuevo tema
            header('Location: ../../vistas/foro/formulario_crear_tema.php');
            exit();
        } else {
            echo "Error: No se pudo guardar el tema.";
        }
        $stmt->close();
    } else {
        echo "Error: No se pudo preparar la consulta.";
    }

    $conn->close();
}
?>
