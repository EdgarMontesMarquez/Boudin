<?php
session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}
 
// Obtener el ID del tema desde la URL (suponiendo que se pasa por GET)
if (!isset($_GET['id'])) {
    // Manejar el caso cuando no se proporciona un ID válido
    header("Location: responder_tema.php"); // Redirigir a la página del foro o manejar el error adecuadamente
    exit();
}

$id_tema = $_GET['id'];

// Conexión a la base de datos
require_once '../../configuraciones/conexion.php'; // Ajusta la ruta según tu estructura

// Consulta para obtener los detalles del tema
$stmtTema = $con->prepare("SELECT temas.id, temas.titulo, temas.descripcion, temas.fecha_creacion, usuarios.NombreUsuario AS NombreCreador
                          FROM temas
                          JOIN usuarios ON temas.id_usuario = usuarios.UserID
                          WHERE temas.id = ?");
$stmtTema->bind_param("i", $id_tema);
$stmtTema->execute();
$resultadoTema = $stmtTema->get_result();

if ($resultadoTema->num_rows === 0) {
    // Manejar el caso cuando no se encuentra el tema con el ID proporcionado
    header("Location: responder_tema.php"); // Redirigir a la página del foro o manejar el error adecuadamente
    exit();
}

$tema = $resultadoTema->fetch_assoc();

// Obtener respuestas asociadas a este tema
$stmtRespuestas = $con->prepare("SELECT respuestas_foro.id, respuestas_foro.respuesta_textual, respuestas_foro.fecha_respuesta, usuarios.NombreUsuario AS NombreUsuario, COUNT(mr.id) AS num_likes
                                 FROM respuestas_foro
                                 JOIN usuarios ON respuestas_foro.id_usuario = usuarios.UserID
                                 LEFT JOIN megusta_respuestas mr ON respuestas_foro.id = mr.id_respuesta
                                 WHERE respuestas_foro.id_tema = ?
                                 GROUP BY respuestas_foro.id");
$stmtRespuestas->bind_param("i", $id_tema);
$stmtRespuestas->execute();
$resultadoRespuestas = $stmtRespuestas->get_result();
$respuestas = $resultadoRespuestas->fetch_all(MYSQLI_ASSOC);


// Obtener el número de respuestas para este tema
$num_respuestas = $resultadoRespuestas->num_rows;


$UserID = $_SESSION['UserID'];
$query = "SELECT RolID FROM usuarios WHERE UserID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$RolID = $row['RolID'];

function barraNavegacion($RolID) {
    // Definir variables para los diferentes menús de navegación
    $estudianteNav = '
        <link rel="icon" type="image/png" href="../../images/captura.png">
        <link rel="stylesheet" href="../../assets/css/modal1.css"/>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <style>
            .w3-theme {color:#fff !important; background-color:#1b4f3e !important}
            .w3-text-theme {color:#1b4f3e !important}
            .w3-border-theme {border-color:#1b4f3e !important}
            .w3-hover-theme:hover {color:#fff !important; background-color:red !important}
            .w3-hover-text-theme:hover {color:red !important}
            .w3-hover-border-theme:hover {border-color:red !important}
            body {
                line-height: 2.15em;
            }
        </style>
        <!-- Header -->
        <div class="w3-bar w3-card w3-top w3-theme">
            <a href="#" class="w3-bar-item" style="color: #fff; text-decoration: none;"><b>Boudin</b></a>
            <a href="../estudiante/estudiante_home.php" class="w3-bar-item w3-hide-small" style="color: #fff; text-decoration: none;">Inicio</a>
            <a href="../estudiante/Estu-actividades.php" class="w3-bar-item w3-hide-small" style="color: #fff; text-decoration: none;">Actividades</a>
            <a href="../estudiante/foro.php" class="w3-bar-item w3-hide-small " style="color: #fff; text-decoration: none;">Foro</a>
            <a onclick="document.getElementById(\'id01\').style.display=\'block\'" class="w3-bar-item w3-hide-small w3-right w3-hover-theme" style="text-decoration: none; color: inherit; cursor: pointer;">Cerrar sesión <i class="fa fa-sign-in"></i></a>
            <a href="javascript:void(0)" class="w3-bar-item w3-right w3-hide-large w3-hide-large w3-card" style=" text-decoration: none; color: #fff; " onclick="myFunction()">&#9776;</a>
        </div>
        <div id="demo" class="w3-bar-block w3-hide w3-hide-large w3-hide-medium">
            <br><br>
            <a href="../estudiante/estudiante_home.php" class="w3-bar-item w3-button" style="color: #000000;">Inicio</a>
            <a href="../estudiante/Estu-actividades.php" class="w3-bar-item w3-button" style="color: #000000;">Actividades</a>
            <a href="../estudiante/foro.php" class="w3-bar-item w3-button" style="color: #000000;">Foro</a>
            <a onclick="document.getElementById(\'id01\').style.display=\'block\'" class="w3-bar-item w3-button" style="text-decoration: none; color: inherit;">Cerrar sesión <i class="fa fa-sign-in"></i></a>
        </div>
        <br>
        <div id="id01" class="modal">
            <span onclick="document.getElementById(\'id01\').style.display=\'none\'" class="close" title="Close Modal">×</span>
            <form class="modal-content" action="/action_page.php" style="border-radius: 10px;">
                <div class="container2">
                    <h1>Cerrar sesión</h1>
                    <p>¿Está seguro de que desea cerrar su sesión?</p>
                    <div class="clearfix">
                        <button type="button" onclick="document.getElementById(\'id01\').style.display=\'none\'" class="cancelbtn">Cancelar</button>
                        <a href="../../controladores/login/logout.php" style="text-decoration: none; color: white;" class="deletebtn">Aceptar</a>
                    </div>
                </div>
            </form>
        </div>
        <script src="../../assets/js/modal1.js"></script>
        <script>
            function myFunction() {
                var x = document.getElementById("demo");
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else {
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>
    ';

    $adminNav = '
        <link rel="icon" type="image/png" href="../../images/captura.png">
        <link rel="stylesheet" href="../../assets/css/modal1.css"/>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <style>
            .w3-theme {color:#fff !important; background-color:#1b4f3e !important}
            .w3-text-theme {color:#1b4f3e !important}
            .w3-border-theme {border-color:#1b4f3e !important}
            .w3-hover-theme:hover {color:#fff !important; background-color:red !important}
            .w3-hover-text-theme:hover {color:red !important}
            .w3-hover-border-theme:hover {border-color:red !important}
            body {
                line-height: 2.15em;
            }
        </style>
        <!-- Header -->
        <div class="w3-bar w3-card w3-top w3-theme">
            <a href="#" class="w3-bar-item" style="color: #fff; text-decoration: none;"><b>Boudin</b></a>
            <a href="../administrador/admin_home.php" class="w3-bar-item w3-hide-small" style="color: #fff; text-decoration: none;">Inicio</a>
            <a href="../administrador/Actividades.php" class="w3-bar-item w3-hide-small" style="color: #fff; text-decoration: none;">Crear Actividades</a>
            <a href="../administrador/Respuestas.php" class="w3-bar-item w3-hide-small" style="color: #fff; text-decoration: none;">Respuestas de Actividades</a>
            <a href="../administrador/foro.php" class="w3-bar-item w3-hide-small " style="color: #fff; text-decoration: none;">Foro</a>
            <a onclick="document.getElementById(\'id01\').style.display=\'block\'" class="w3-bar-item w3-hide-small w3-right w3-hover-theme" style="text-decoration: none; color: inherit; cursor: pointer;">Cerrar sesión <i class="fa fa-sign-in"></i></a>
            <a href="javascript:void(0)" class="w3-bar-item w3-right w3-hide-large w3-hide-large w3-card" style=" text-decoration: none; color: #fff; " onclick="myFunction()">&#9776;</a>
        </div>
        <div id="demo" class="w3-bar-block w3-hide w3-hide-large w3-hide-medium">
            <br><br>
            <a href="../administrador/admin_home.php" class="w3-bar-item w3-button" style="color: #000000;">Inicio</a>
            <a href="../administrador/Actividades.php" class="w3-bar-item w3-button" style="color: #000000;">Crear Actividades</a>
            <a href="../administrador/Respuestas.php" class="w3-bar-item w3-button" style="color: #000000;">Respuestas de Actividades</a>
            <a href="../administrador/foro.php" class="w3-bar-item w3-button" style="color: #000000;">Foro</a>
            <a onclick="document.getElementById(\'id01\').style.display=\'block\'" class="w3-bar-item w3-button" style="text-decoration: none; color: inherit;">Cerrar sesión <i class="fa fa-sign-in"></i></a>
        </div>
        <br>
        <div id="id01" class="modal">
            <span onclick="document.getElementById(\'id01\').style.display=\'none\'" class="close" title="Close Modal">×</span>
            <form class="modal-content" action="/action_page.php" style="border-radius: 10px;">
                <div class="container2">
                    <h1>Cerrar sesión</h1>
                    <p>¿Está seguro de que desea cerrar su sesión?</p>
                    <div class="clearfix">
                        <button type="button" onclick="document.getElementById(\'id01\').style.display=\'none\'" class="cancelbtn">Cancelar</button>
                        <a href="../../controladores/login/logout.php" style="text-decoration: none; color: white;" class="deletebtn">Aceptar</a>
                    </div>
                </div>
            </form>
        </div>
        <script src="../../assets/js/modal1.js"></script>
        <script>
            function myFunction() {
                var x = document.getElementById("demo");
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else {
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>
    ';

    // Mostrar la barra de navegación según el RolID
    if ($RolID == 1) {
        echo $estudianteNav;
    } elseif ($RolID == 2) {
        echo $adminNav;
    } else {
        // Manejar caso no esperado (puedes imprimir un mensaje de error o redirigir)
        echo "RolID no válido";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver y Responder Tema - <?php echo htmlspecialchars($tema['titulo']); ?></title>
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <link rel="stylesheet" href="../../assets/css/modal1.css"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos originales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            background-color: #1B4F3E;
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 12px 12px 0 0;
            padding: 15px;
        }

        .card-body {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-subtitle {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .card-text {
            color: #333;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 4px;
        }

        .btn-responder {
            background-color: #1B4F3E;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 4px;
            border-width: 1px;
            border-color: #1B4F3E;
        }

        .btn-responder:hover {
            background-color: #155c48;
        }

        .respuesta {
            border-top: 1px solid #ccc;
            padding-top: 15px;
            margin-top: 15px;
        }

        /* Estilos adicionales */
        .responses {
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Alineación a la derecha */
            margin-bottom: 10px;
        }

        .responses i {
            font-size: 24px;
            color: #666;
            margin-right: 5px;
        }

        .responses span {
            font-size: 24px;
            color: #666;
            margin-left: 5px;
        }
    </style>
        <style>
      .w3-theme {color:#fff !important; background-color:#1b4f3e !important}
      .w3-text-theme {color:#1b4f3e !important}
      .w3-border-theme {border-color:#1b4f3e !important}

      .w3-hover-theme:hover {color:#fff !important; background-color:red !important}
      .w3-hover-text-theme:hover {color:red !important}
      .w3-hover-border-theme:hover {border-color:red !important}

      
        body {
            line-height: 2.15em;
          }
          .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        @media (min-width: 576px) {
            .btn-container {
                display: flex;
                justify-content: space-between;
            }
        }
        .btn-guardar:hover {
            background-color: #155c48;
        }

        .btn-cancelar {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }

        .btn-cancelar:hover {
            background-color: #5a6268;
        }
        .btn-cancelar {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php barraNavegacion($RolID); ?>
    <div class="container">
        <div class="card">
            <div class="card-header">Ver y Responder Tema</div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-letf mb-3">
                            <?php
                            // Obtener la inicial del nombre del creador
                            $inicial = strtoupper(substr($tema['NombreCreador'], 0, 1));
                            // URL de la imagen predeterminada con la inicial
                            $imagen_predeterminada = "https://via.placeholder.com/40?text=$inicial"; // Puedes usar otra URL o servicio para generar esta imagen
                            ?>
                            <img src="<?php echo $imagen_predeterminada; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <div>
                                <h5 class="card-title"><?php echo htmlspecialchars($tema['titulo']); ?></h5>
                                <p class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($tema['NombreCreador']); ?> | <?php echo date('d/m/Y', strtotime($tema['fecha_creacion'])); ?></p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($tema['descripcion'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="responses">
                    <div class="num-respuestas-icon"><i class="fa fa-comments"></i></div>
                    <div class="num-respuestas-text">
                        <span><?php echo $num_respuestas; ?></span>
                    </div>
                </div>

                <h5>Respuestas:</h5>

                <?php foreach ($respuestas as $respuesta) { ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                            <?php
                                // Obtener la inicial del nombre del creador
                                $inicial = strtoupper(substr($tema['NombreCreador'], 0, 1));
                                // URL de la imagen predeterminada con la inicial
                                $imagen_predeterminada = "https://via.placeholder.com/40?text=$inicial"; // Puedes usar otra URL o servicio para generar esta imagen
                                ?>
                                <img src="<?php echo $imagen_predeterminada; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                                <div>
                                    <div class="card-title mb-2 text-muted"><?php echo htmlspecialchars($respuesta['NombreUsuario']); ?></div>
                                    <div class="card-subtitle mb-2 text-muted"><?php echo date('d/m/Y H:i', strtotime($respuesta['fecha_respuesta'])); ?></div>
                                </div>
                            </div>
                            <p class="card-text" style="margin-left: 50px;"><?php echo htmlspecialchars($respuesta['respuesta_textual']); ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <!-- Mostrar el número de Me Gusta y botón para dar Me Gusta -->
                            <span><?php echo $respuesta['num_likes']; ?> Me Gusta</span>
                            <!-- Dentro del bucle foreach para mostrar respuestas -->
                                <form class="formMeGusta" method="post">
                                    <input type="hidden" name="id_respuesta" value="<?php echo $respuesta['id']; ?>">
                                    <button type="submit" class="btn btn-outline-primary btnMeGusta"><i class="fa fa-thumbs-up"></i> Me Gusta</button>
                                </form>


                        </div>
                    </div>
                <?php } ?>



                <!-- Formulario para responder -->
                <form action="../../controladores/foro/procesar_respuesta.php" method="POST">
                    <div class="form-group">
                        <label for="texto">Responder al Tema:</label>
                        <textarea class="form-control" id="texto" name="texto" rows="5" required></textarea>
                    </div>
                    <div class="btn-container">
                    <?php
                        // Botón "Atrás" condicional según el RolID
                        if ($_SESSION['RolID'] == 1) {
                            echo '<a href="../estudiante/foro.php" class="btn btn-cancelar"><i class="fa fa-arrow-left"></i> Atrás</a>';
                        } elseif ($_SESSION['RolID'] == 2) {
                            echo '<a href="../administrador/foro.php" class="btn btn-cancelar"><i class="fa fa-arrow-left"></i> Atrás</a>';
                        }
                        ?>
                    <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>">
                    <button type="submit" class="btn btn-responder"><i class="fa fa-reply"></i> Responder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="footer">
        &copy; Boudin. Todos los derechos reservados.
    </div>
    <!-- Bootstrap JS y jQuery (opcional, si se usan funcionalidades de Bootstrap que lo requieran) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Al final del archivo HTML -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.formMeGusta').submit(function(e) {
        e.preventDefault(); // Prevenir la acción predeterminada del formulario

        // Obtener datos del formulario
        var formData = $(this).serialize();

        // Realizar la solicitud AJAX
        $.ajax({
            type: 'POST',
            url: '../../controladores/foro/guardar_voto.php', // Ruta correcta al controlador PHP
            data: formData,
            dataType: 'json', // Tipo de datos esperados en la respuesta
            success: function(response) {
                if (response.success) {
                    // Actualizar el contador de likes en la interfaz
                    var num_likes = response.num_likes;
                    $(e.target).find('.numLikes').text(num_likes);

                    // Opcional: Cambiar el estilo del botón o mostrar un mensaje de confirmación
                    $(e.target).find('.btnMeGusta').addClass('btn-success'); // Cambia el color del botón a verde por ejemplo
                } else {
                    // Manejar errores si es necesario
                    console.error(response.error);
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                console.error(xhr.responseText);
            }
        });
    });
});
</script>


</body>
</html>
