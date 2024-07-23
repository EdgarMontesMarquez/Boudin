<?php
session_start();
require_once '../../configuraciones/conexion.php';

if (!isset($_SESSION['UserID'])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión.
    header('Location: ../principal/index.php');
    exit();
}

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

// Verificar si el usuario tiene el rol de administrador
if ($rolUsuario != 2) {
    // El usuario no tiene permisos de administrador, redirigir a una página de acceso no autorizado.
    session_destroy(); // Destruir la sesión actual
    header('Location: ../login/loginYregistro.php');
    exit();
}

// Obtener datos de actividades y el número de respuestas
$sql = "
    SELECT a.id, a.nombre AS nombre_actividad, COUNT(r.id) AS num_respuestas
    FROM actividades a
    LEFT JOIN respuestas r ON a.id = r.id_actividad
    GROUP BY a.id, a.nombre
";
$resultado = mysqli_query($con, $sql);

if (!$resultado) {
    // Manejar el error, por ejemplo, redirigir a una página de error.
    header('Location: error.php');
    exit();
}

$actividades = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $actividades[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Respuesta actividades</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        input.w3-input {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        table.w3-table-all {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.w3-table-all th, table.w3-table-all td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table.w3-table-all th {
            background-color: #f2f2f2;
        }

        table.w3-table-all tr:hover {
            background-color: #f1f1f1;
        }

        table.w3-table-all tr {
            cursor: pointer;
        }

        @media (max-width: 600px) {
            table.w3-table-all th, table.w3-table-all td {
                font-size: 14px;
            }

            h2 {
                font-size: 20px;
            }
        }

        .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            clear: both;
        }
    </style>
</head>
<body>
<?php require_once "Header_admin.php"; ?>
<br>
<div class="container">
    <h2>Filtro para buscar actividades</h2>
    <p>Busca la actividad que deseas ver solo escribiendo su nombre.</p>

    <input class="w3-input w3-border w3-padding" type="text" placeholder="Buscar actividad.." id="myInput" onkeyup="filterActivities()">

    <table class="w3-table-all w3-margin-top" id="myTable">
        <thead>
            <tr>
                <th style="width:60%;">Nombre de la actividad</th>
                <th style="width:40%;">N° Respuestas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actividades as $actividad): ?>
            <tr onclick="location.href='ver_respuestas.php?id=<?php echo $actividad['id']; ?>'">
                <td><?php echo htmlspecialchars($actividad['nombre_actividad']); ?></td>
                <td><?php echo htmlspecialchars($actividad['num_respuestas']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function filterActivities() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            tr[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? "" : "none";
        }
    }
}
</script>
<br><br><br><br><br>
<div class="footer">&copy; Boudin. Todos los derechos reservados.</div>
</body>
</html>
