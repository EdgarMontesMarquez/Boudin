<?php
require_once '../../configuraciones/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['nombre_usuario']) &&
        isset($_POST['email']) &&
        isset($_POST['password']) &&
        isset($_POST['confirmar_password']) &&
        isset($_POST['num_dni'])
    ) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmar_password = $_POST['confirmar_password'];
        $num_dni = $_POST['num_dni'];

        // Verificar si las contraseñas coinciden
        if ($password !== $confirmar_password) {
            echo "Las contraseñas no coinciden";
            exit();
        }

        // Verificar si el correo electrónico ya está registrado
        $check_email_query = "SELECT * FROM Usuarios WHERE Email = '$email'";
        $check_email_result = $con->query($check_email_query);

        if ($check_email_result->num_rows > 0) {
            echo "El correo electrónico ya está registrado";
            exit();
        }

        // Verificar si el nombre de usuario ya está registrado
        $check_username_query = "SELECT * FROM Usuarios WHERE NombreUsuario = '$nombre_usuario'";
        $check_username_result = $con->query($check_username_query);

        if ($check_username_result->num_rows > 0) {
            echo "El nombre de usuario ya está registrado";
            
            exit();
        }

        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo estudiante en la base de datos
        $insert_user_query = "INSERT INTO Usuarios (NombreUsuario, Email, Contraseña, num_dni, RolID) VALUES ('$nombre_usuario', '$email', '$hashed_password', '$num_dni', 1)";

        if ($con->query($insert_user_query) === TRUE) {
            echo "Registro exitoso";
            // Limpiar campos del formulario redefiniendo los valores
            

        } else {
            echo "Error al registrar el usuario: " . $con->error;
        }
    } else {
        echo "Datos del formulario no recibidos correctamente";
    }
}

$con->close();
?>
