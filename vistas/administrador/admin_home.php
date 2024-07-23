<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión.
    header('Location: ../principal/index.php');
    exit();
}

require_once '../../configuraciones/conexion.php';

// Obtén el RolID del usuario actual
$UserID = $_SESSION['UserID'];
$stmt = $con->prepare("SELECT RolID FROM usuarios WHERE UserID = ?");
$stmt->bind_param("i", $UserID);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    // Manejar el error, por ejemplo, redirigir a una página de error.
    header('Location: error.php');
    exit();
}

$row = $resultado->fetch_assoc();
$rolUsuario = $row['RolID'];

// Verificar si el usuario tiene el rol de administrador (supongamos que el rol de administrador es 1)
if ($rolUsuario != 2) {
    // El usuario no tiene permisos de administrador, redirigir a una página de acceso no autorizado.
    session_destroy(); // Destruir la sesión actual
    header('Location: ../login/loginYregistro.php');
    exit();
}

// Obtener datos para el dashboard
// Número de usuarios registrados con RolID 1
$stmtUsuarios = $con->prepare("SELECT COUNT(*) AS total_usuarios FROM usuarios WHERE RolID = 1");
$stmtUsuarios->execute();
$resultadoUsuarios = $stmtUsuarios->get_result();
$totalUsuarios = $resultadoUsuarios->fetch_assoc()['total_usuarios'];

// Número de actividades creadas
$stmtActividades = $con->prepare("SELECT COUNT(*) AS total_actividades FROM actividades");
$stmtActividades->execute();
$resultadoActividades = $stmtActividades->get_result();
$totalActividades = $resultadoActividades->fetch_assoc()['total_actividades'];

// Número de respuestas recibidas
$stmtRespuestas = $con->prepare("SELECT COUNT(*) AS total_respuestas FROM respuestas");
$stmtRespuestas->execute();
$resultadoRespuestas = $stmtRespuestas->get_result();
$totalRespuestas = $resultadoRespuestas->fetch_assoc()['total_respuestas'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Inicio - Administrador</title>
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .header img {
            width: 50px;
            vertical-align: middle;
        }

        .header h1 {
            display: inline;
            margin: 0;
            vertical-align: middle;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .nav {
            display: flex;
            justify-content: space-around;
            background: #444;
            color: #fff;
            padding: 10px 0;
        }

        .nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
        }

        .nav a:hover {
            background: #555;
            border-radius: 5px;
        }

        .content {
            margin-top: 20px;
        }

        .dashboard {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .card {
            background: #eee;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1 1 200px;
            text-align: center;
        }

        .card h3 {
            margin-top: 0;
        }

        .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../../images/captura.png" alt="Logo">
        <br><br>
        <h1>Boudin | Panel de Administración</h1>
    </div>

    <?php require_once "Header_admin.php"; ?>

   

    <div class="container">
        <div class="content">
            <h2>Bienvenido, Administrador</h2>
            <p>Desde aquí puedes gestionar todas las actividades y usuarios de la plataforma.</p>

            <div class="dashboard">
                <div class="card">
                    <h3>Usuarios Registrados</h3>
                    <p><?php echo $totalUsuarios; ?></p>
                </div>
                <div class="card">
                    <h3>Actividades Creadas</h3>
                    <p><?php echo $totalActividades; ?></p>
                </div>
                <div class="card">
                    <h3>Respuestas Recibidas</h3>
                    <p><?php echo $totalRespuestas; ?></p>
                </div>
            </div>
        </div>
    </div>
<br><br>
    <div class="footer">
        &copy; Boudin. Todos los derechos reservados.
    </div>
</body>
</html>
