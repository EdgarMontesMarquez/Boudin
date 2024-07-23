<?php
session_start();
require_once '../../configuraciones/conexion.php';

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}

// Obtener el RolID del usuario actual
$UserID = $_SESSION['UserID'];
$sql = "SELECT RolID FROM usuarios WHERE UserID = $UserID";
$resultado = mysqli_query($con, $sql);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    header('Location: error.php');
    exit();
}

$row = mysqli_fetch_assoc($resultado);
$rolUsuario = $row['RolID'];

// Verificar si el usuario tiene el rol de administrador (RolID = 2)
if ($rolUsuario != 2) {
    session_destroy(); // Destruir la sesión actual por seguridad
    header('Location: ../login/loginYregistro.php');
    exit();
}

// Obtener el ID de la actividad desde la URL
$actividad_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener detalles de la actividad
$sql_actividad = "SELECT * FROM actividades WHERE id = $actividad_id";
$resultado_actividad = mysqli_query($con, $sql_actividad);

if (!$resultado_actividad || mysqli_num_rows($resultado_actividad) == 0) {
    header('Location: error.php');
    exit();
}

$actividad = mysqli_fetch_assoc($resultado_actividad);

// Ruta para la imagen de la actividad
$ruta_imagen_actividad = '../../images/imagenes/' . $actividad['imagen'];

// Obtener respuestas de la actividad
$sql_respuestas = "
    SELECT r.*, u.NombreUsuario AS nombre_usuario, r.imagen AS imagen_respuesta
    FROM respuestas r
    JOIN usuarios u ON r.id_usuario = u.UserID
    WHERE r.id_actividad = $actividad_id
";
$resultado_respuestas = mysqli_query($con, $sql_respuestas);

if (!$resultado_respuestas) {
    header('Location: error.php');
    exit();
}

$respuestas = [];
while ($row = mysqli_fetch_assoc($resultado_respuestas)) {
    $respuestas[] = $row;
}

// Ruta base para las imágenes de respuestas
$ruta_base_imagenes_respuestas = '../../images/imagenes/respuestas/';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Respuestas de la actividad</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="icon" type="image/png" href="../../images/captura.png">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .respuesta-img:hover {
            opacity: 0.7;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }

        .close:hover, .close:focus {
            color: #bbb;
            text-decoration: none;
        }

        /* Estilos para la tabla */
        .w3-table-all {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
        }

        .w3-table-all th, .w3-table-all td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .w3-table-all th {
            background-color: #f2f2f2;
        }

        .w3-table-all tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .w3-table-all tbody tr:hover {
            background-color: #f1f1f1;
        }

        .w3-table-all img {
            width: 100%;
            max-width: 150px;
            height: auto;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .w3-table-all img:hover {
            opacity: 0.7;
        }

        /* Estilos para la imagen principal */
        .actividad-imagen {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 15px;
            display: block;
            margin: 0 auto;
        }

        /* Estilos para el contenedor */
        .container {
            max-width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Estilos para el pie de página */
        .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            clear: both;
        }

        /* Estilos para el texto de la actividad */
        .actividad-texto {
            text-align: left;
            margin-bottom: 20px;
        }

        .actividad-titulo {
            text-align: left;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Estilos para dispositivos móviles */
        @media only screen and (max-width: 600px) {
            .w3-table-all th, .w3-table-all td {
                padding: 8px;
                font-size: 14px;
            }

            .actividad-titulo {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <?php require_once "Header_admin.php"; ?>
    <div class="container"><br>
        <h2 class="actividad-titulo"><?php echo htmlspecialchars($actividad['nombre']); ?></h2>
        <br>
        <?php if (!empty($actividad['imagen']) && file_exists($ruta_imagen_actividad)): ?>
            <img class="actividad-imagen" src="<?php echo $ruta_imagen_actividad; ?>" alt="Imagen de la actividad">
        <?php else: ?>
            <p>No hay imagen para esta actividad.</p>
        <?php endif; ?>
        <p class="actividad-texto"><?php echo htmlspecialchars($actividad['descripcion']); ?></p>

        <div class="w3-responsive">
            <table class="w3-table-all">
                <thead>
                    <tr>
                        <th style="width: 20%;">Nombre</th>
                        <th style="width: 40%;">Respuesta</th>
                        <th style="width: 40%;">Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($respuestas as $respuesta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($respuesta['nombre_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($respuesta['respuesta_textual']); ?></td>
                            <td>
                                <?php if (!empty($respuesta['imagen_respuesta']) && file_exists($ruta_base_imagenes_respuestas . $respuesta['imagen_respuesta'])): ?>
                                    <img class="respuesta-img" width="200" height="auto" src="<?php echo $ruta_base_imagenes_respuestas . $respuesta['imagen_respuesta']; ?>" alt="Imagen de la respuesta" onclick="openModal(this);">
                                <?php else: ?>
                                    No hay imagen
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para mostrar las imágenes en grande -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImg">
        <div id="caption"></div>
    </div>

    <script>
        // Función para abrir el modal y mostrar la imagen seleccionada
        function openModal(imgElement) {
            var modal = document.getElementById('myModal');
            var modalImg = document.getElementById('modalImg');
            var captionText = document.getElementById('caption');
            
            modal.style.display = "block";
            modalImg.src = imgElement.src;
            captionText.innerHTML = imgElement.alt;
        }

        // Función para cerrar el modal
        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }

        // Cerrar el modal si el usuario hace clic fuera del contenido
        window.onclick = function(event) {
            var modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <br>

    <div class="footer">&copy; Boudin. Todos los derechos reservados.</div>
</body>
</html>
