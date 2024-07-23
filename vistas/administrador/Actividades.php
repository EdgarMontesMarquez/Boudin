<?php

session_start();
require_once '../../configuraciones/conexion.php';

if (!isset($_SESSION['UserID'])) {
  // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión.
  header('Location: ../principal/index.php');
  exit(); // Asegúrate de salir después de redirigir.
}


// Obtén el RolID del usuario actual
$UserID = $_SESSION['UserID'];
$sql = "SELECT RolID FROM usuarios WHERE UserID = $UserID";
$resultado = mysqli_query($con, $sql);

if (!$resultado) {
  // Manejar el error, por ejemplo, redirigir a una página de error.
  header('Location: error.php');
  exit();
}

$row = mysqli_fetch_assoc($resultado);
$rolUsuario = $row['RolID'];

// Verificar si el usuario tiene el rol de administrador ()
if ($rolUsuario != 2) {
  // El usuario no tiene permisos de administrador, redirigir a una página de acceso no autorizado.
  session_destroy(); // Destruir la sesión actual
  header('Location: ../login/loginYregistro.php');
  exit();
}

// Continuar con el contenido de la página solo si el usuario es un administrador.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Crear Actvidades</title>
    <link rel="stylesheet" href="../../assets/css/card.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="../../images/captura.png">

<style>
    .prueba{
        column-count:2;
    }
    .prueba2{
        column-count:1;
    }
    #descripcion {
        max-width: 100%;
        height: 150px;
    }
    .titu{
    text-align: left;
    }



@media only screen and (max-width: 758px) {
/* For mobile phones: */
    .prueba, .prueba2 {
        column-count:1;

    }
    .container,{
        width: 100%;
    }
    .card{
    text-align: center;
    }
    .responsive-table-title{
        display:none;
    }
    #descripcion {
        max-width: 100%;
        height: 150px;
    }
   
}
@media only screen and (max-width: 360px) {
/* For mobile phones: */
    .prueba, .prueba2 {
        column-count:1;

    }
    .container,{
        width: 100%;
    }
    .card{
    text-align: center;
    }
#descripcion {
        max-width: 100%;
        height: 150px;
    }

}
</style>
</head>
<body>
    <?php
    require_once "Header_admin.php";
    ?>
<div class="container">
    <h1 class="titu">Actividades</h1>
    <!-- creamos formulario para las actividades que vamos a subir, el diseño de este formulario lo vamos hacer tipo card -->
    <div class="card titu">
        <?php
            if(isset($_SESSION['error'])){
                echo '<p style="color: green;">'.$_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
    
        <h1 class="mt-3 mb-3"> Crear actividades</h1>
        <form action="../../controladores/administrador/guardar_actividades.php" method="post" enctype="multipart/form-data">

            <div class="prueba">
                <div class="form-group">
                    <label for="nombre">Nombre de la actividad:</label>
                    <input type="text" id="nombre" class="form-control" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="fecha_vencimiento">Fecha y Hora de Vencimiento de la actividad:</label>
                    <input type="datetime-local" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" required>
                </div>
            </div>
            <div class="prueba2">
                <div class="form-group">
                    <label for="imagen">Imagen de presentación:</label>
                    <input type="file" class="form-control" name="imagen" id="imagen" required> 
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción de la actividad:</label>
                    <textarea type="text" class="form-control" id="descripcion" name="descripcion" required></textarea>
                </div>
            </div>
            

            <button type="submit" class="btn btn-primary btn-block">Guardar</button>
        </form>
    </div>
    <!-- tabla para mostrar las actividades creadas -->
    <br>
    <div class="card">
      <div class="table-responsive">
        <h1>Tabla Actividades</h1>
        <table class="table" >
            <thead class="thead-light">
                <tr>
                    <th class="responsive-table-title">ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha de creación</th>
                    <th>Fecha y Hora de Vencimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- en esta parte va el codigo para mostrar los datos que se guardan en la base de datos  -->
            <tbody>
                <?php
                    require_once '../../configuraciones/conexion.php';
                    //realizamos una consulta a la sabe de datos para obtener las actividades
                    $sql = "SELECT * FROM actividades";
                    $resultado = mysqli_query($con,$sql);

                    //en esta parte se recorrer el resultado de la consulta y mostramos los datos en la tabla
                    while($fila = mysqli_fetch_assoc($resultado)){
                        echo "<tr>";
                        echo "<td class='responsive-table-title'>" . $fila['id'] . "</td>";
                        echo "<td><img src ='../../images/imagenes/" . $fila['imagen'] . "' alt='Imagen de la actividad' width = '100'></td>";
                        echo "<td>" . $fila['nombre'] . "</td>";
                        echo "<td>" . $fila['descripcion'] . "</td>";
                        echo "<td>" . $fila['fecha_creacion'] . "</td>";
                        echo "<td>" . $fila['fecha_vencimiento'] . "</td>";
                        echo "<td syle='display:flex;'>";
                        echo "<form action='../../controladores/administrador/borrar-actividad.php' method='POST' onsubmit='return confirm('¿Estás seguro que deseas eliminar esta actividad". $fila['nombre']."?');'>";
                        echo "<input type='hidden' name='id' value='" . $fila['id']. "'>";
                        echo "<button  type=submit class='btn-table'><i class='fas fa-trash' ></i> Eliminar</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";

                    }
                ?>
            </tbody>
        </table>
      </div>
    </div>
</div>
<div style="background-color:#e5e5e5;text-align:center;padding:10px;margin-top:7px;">&copy; Boudin. Todos los derechos reservados.</div>
</body>
</html>