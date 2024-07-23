<?php
session_start();
require_once '../../configuraciones/conexion.php';

$response = array(); // Array para la respuesta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT UserID, NombreUsuario, Contraseña, RolID FROM Usuarios WHERE Email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['Contraseña'];

            // Verificar la contraseña utilizando password_verify
            if (password_verify($password, $hashed_password)) {
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['NombreUsuario'] = $row['NombreUsuario'];
                $_SESSION['RolID'] = $row['RolID'];

                // Destruir la antigua sesión y generar una nueva
                session_regenerate_id(true);

                // Redirigir según el rol
                if ($row['RolID'] == 1) {
                    $response['redirect'] = '../../vistas/estudiante/estudiante_home.php';
                } elseif ($row['RolID'] == 2) {
                    $response['redirect'] = '../../vistas/administrador/admin_home.php';
                } else {
                    $response['error'] = 'Rol no reconocido';
                }
            } else {
                $response['error'] = 'Credenciales inválidas';
            }
        } else {
            $response['error'] = 'Credenciales inválidas';
        }

        $stmt->close();
    } else {
        $response['error'] = 'Datos del formulario no recibidos correctamente';
    }
}

$con->close();

// Devolver respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
