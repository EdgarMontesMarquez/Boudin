<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión.
    header('Location: ../principal/index.php');
    exit();
}

require_once '../../configuraciones/conexion.php';

// Verificar la conexión a la base de datos
if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Obtén el RolID del usuario actual
$UserID = $_SESSION['UserID'];
$stmt = $con->prepare("SELECT RolID, NombreUsuario FROM usuarios WHERE UserID = ?");
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
$nombreUsuario = $row['NombreUsuario'];

// Verificar si el usuario tiene el rol de estudiante (supongamos que el rol de estudiante es 1)
if ($rolUsuario != 1) {
    // El usuario no tiene permisos de estudiante, redirigir a una página de acceso no autorizado.
    session_destroy(); // Destruir la sesión actual
    header('Location: ../login/loginYregistro.php');
    exit();
}

// Consulta para contar el número total de actividades
$stmtCountActividades = $con->prepare("SELECT COUNT(*) AS total FROM actividades");
$stmtCountActividades->execute();
$resultadoCountActividades = $stmtCountActividades->get_result();
$rowCountActividades = $resultadoCountActividades->fetch_assoc();
$totalActividades = $rowCountActividades['total'];

// Consulta para contar el número de actividades realizadas por el estudiante
$stmtCountRealizadas = $con->prepare("SELECT COUNT(*) AS totalRealizadas FROM respuestas WHERE id_usuario = ? AND (respuesta_textual IS NOT NULL OR imagen IS NOT NULL)");
$stmtCountRealizadas->bind_param("i", $UserID);
$stmtCountRealizadas->execute();
$resultadoCountRealizadas = $stmtCountRealizadas->get_result();
$rowCountRealizadas = $resultadoCountRealizadas->fetch_assoc();
$totalActividadesRealizadas = $rowCountRealizadas['totalRealizadas'];

// Calcular el número de actividades no realizadas
$totalActividadesNoRealizadas = $totalActividades - $totalActividadesRealizadas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Inicio - Estudiante</title>
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
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .dashboard {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }


        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: 300px;
            text-align: center;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            margin-top: 0;
            color: #333;
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
    <?php require_once "Header_estudiante.php"; ?>

    <div class="header">
        <br>
        <h1>Boudin | Panel de Estudiante</h1>
    </div>
<br>
    <div class="container">
        <div class="content">
            <h2>Bienvenido, <?php echo $nombreUsuario; ?></h2>
            <div class="dashboard">
                <div class="card">
                    <h3>Número total de Actividades</h3>
                    <p><?php echo $totalActividades; ?></p>
                </div>

                <div class="card">
                    <h3>Número total de Actividades Realizadas</h3>
                    <p><?php echo $totalActividadesRealizadas; ?></p>
                </div>

                <div class="card">
                    <h3>Número total de Actividades No Realizadas</h3>
                    <p><?php echo $totalActividadesNoRealizadas; ?></p>
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
