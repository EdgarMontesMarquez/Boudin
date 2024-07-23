<?php
session_start();

// Verificar la sesión del usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../../vistas/login/loginYregistro.php');
    exit;
}

require_once '../../configuraciones/conexion.php';
require_once '../../configuraciones/config.php';
 
// Recoger datos del formulario
$id_usuario = $_SESSION['UserID'];
$id_actividad = $_POST['id_actividad'];
$respuesta_textual = $_POST['respuesta_textual'];

// Procesar la imagen si se ha adjuntado
$imagen_nombre = null;
if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name'];
    move_uploaded_file($_FILES['imagen']['tmp_name'], '../../images/imagenes/respuestas/' . $imagen_nombre);
}

// Verificar el token
$token = $_POST['token'];
$token_tmp = hash_hmac("sha1", $id_actividad, KEY_TOKEN);
if ($token !== $token_tmp) {
    // Token no válido, tomar medidas adecuadas (puede ser redireccionar, mostrar un error, etc.)
    echo "Token no válido";
    exit;
}

// Verificar si el usuario ya realizó la actividad
$sql_verificacion = "SELECT id FROM Respuestas WHERE id_usuario = ? AND id_actividad = ?";
$stmt_verificacion = mysqli_prepare($con, $sql_verificacion);

if ($stmt_verificacion) {
    mysqli_stmt_bind_param($stmt_verificacion, 'ii', $id_usuario, $id_actividad);
    mysqli_stmt_execute($stmt_verificacion);
    mysqli_stmt_store_result($stmt_verificacion);

    // Si ya existe una respuesta, redirigir 
    if (mysqli_stmt_num_rows($stmt_verificacion) > 0) {
        echo "Ya has realizado esta actividad anteriormente.";
        exit;
    }
} else {
    // Manejar el error de la preparación de la consulta de verificación
    echo "Error en la preparación de la consulta: " . mysqli_error($con);
    exit;
}

// Insertar datos en la tabla Respuestas
$sql_insercion = "INSERT INTO Respuestas (id_usuario, id_actividad, respuesta_textual, imagen) 
                  VALUES (?, ?, ?, ?)";
$stmt_insercion = mysqli_prepare($con, $sql_insercion);

if ($stmt_insercion) {
    mysqli_stmt_bind_param($stmt_insercion, 'iiss', $id_usuario, $id_actividad, $respuesta_textual, $imagen_nombre);

    if (mysqli_stmt_execute($stmt_insercion)) {
        // Éxito: Redirigir a la página de actividades o mostrar un mensaje de éxito
        echo "Respuesta enviada con éxito.";
        exit;
    } else {
        // Manejar el error de la consulta SQL de inserción
        echo "Error en la ejecución de la consulta de inserción: " . mysqli_error($con);
        exit;
    }

    mysqli_stmt_close($stmt_insercion);
} else {
    // Manejar el error de la preparación de la consulta de inserción
    echo "Error en la preparación de la consulta de inserción: " . mysqli_error($con);
    exit;
}

mysqli_close($con);
?>
