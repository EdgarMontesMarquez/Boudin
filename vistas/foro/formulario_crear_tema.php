<?php
session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['UserID'])) {
    header('Location: ../principal/index.php');
    exit();
}

// Conectar a la base de datos
require_once '../../configuraciones/conexion.php'; // Asegúrate de incluir el archivo correcto para la conexión a la base de datos

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
    <title>Crear Nuevo Tema - Foro de Discusión</title>
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <link rel="stylesheet" href="../../assets/css/modal1.css"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 2.15em;
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
            border-radius: 0 0 12px 12px;
            padding: 20px;
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

        .btn-guardar, .btn-cancelar {
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

        .btn-guardar {
            background-color: #1B4F3E;
            color: white;
            border: 1px solid #1B4F3E;
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

        .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        .w3-theme {color:#fff !important; background-color:#1b4f3e !important}
        .w3-text-theme {color:#1b4f3e !important}
        .w3-border-theme {border-color:#1b4f3e !important}
        .w3-hover-theme:hover {color:#fff !important; background-color:red !important}
        .w3-hover-text-theme:hover {color:red !important}
        .w3-hover-border-theme:hover {border-color:red !important}

        @media (min-width: 576px) {
            .btn-container {
                display: flex;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    
<?php barraNavegacion($RolID); ?>

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
<br>
    <!-- Contenido Principal -->
    <div class="container">
        <div class="card">
            <div class="card-header">Crear Nuevo Tema</div>
            <div class="card-body">
                <!-- Formulario de Creación de Tema -->
                <form action="../../controladores/foro/procesar_creacion_tema.php" method="POST">
                    <div class="form-group">
                        <label for="titulo">Título del Tema</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
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

                        <button type="submit" class="btn btn-guardar"><i class="fa fa-save"></i> Guardar Tema</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        &copy; Boudin. Todos los derechos reservados.
    </div>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
