<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../assets/css/estiloscards.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="icon" type="image/png" href="../../images/captura.png">

<style>
  .ribbon {
    width: 60px;
    font-size: 14px;
    padding: 4px;
    position: absolute;
    right: -25px;
    top: -12px;
    text-align: center;
    border-radius: 25px;
    transform: rotate(20deg);
    background-color: #00D26A;
    color: white;
  }
  .ribbon2 {
    width: 60px;
    font-size: 14px;
    padding: 4px;
    position: absolute;
    right: -25px;
    top: -12px;
    text-align: center;
    border-radius: 25px;
    transform: rotate(20deg);
    background-color: red;
    color: white;
  }
  .container {
    max-width: 1200px; /* Aumenta el ancho del contenedor */
  }
  .card {
    border-radius: 15px; /* Tarjetas más redondeadas */
    width: 90%; /* Reduce el ancho de las tarjetas */
    margin: 0 auto; /* Centra las tarjetas */
  }
  .gallery {
    margin-bottom: 20px; /* Espacio entre las tarjetas */
  }
</style>
</head>
<body>
<br><br>

<div class="container">

<h2>ACTIVIDADES PENDIENTES</h2>
<br><br>
  <div class="row">
      
    <!-- cards -->
    <?php
      require_once '../../configuraciones/conexion.php';
      require_once '../../configuraciones/config.php';

      $sql = "SELECT * FROM actividades WHERE fecha_vencimiento > NOW() ORDER BY fecha_creacion DESC";
      
      $res = mysqli_query($con, $sql);

      while ($reg = mysqli_fetch_assoc($res)) {
        $actividadId = $reg["id"];
        $token = hash_hmac("sha1", $actividadId, KEY_TOKEN);
        
        $usuarioId = $_SESSION['UserID'];
        $sqlUsuarioRealizado = "SELECT COUNT(*) as num FROM Respuestas WHERE id_usuario = $usuarioId AND id_actividad = $actividadId";

        $resUsuarioRealizado = mysqli_query($con, $sqlUsuarioRealizado);

        if (!$resUsuarioRealizado) {
            die("Error en la consulta SQL: " . mysqli_error($con));
        }

        $rowUsuarioRealizado = mysqli_fetch_assoc($resUsuarioRealizado);
        $usuarioHaRealizado = ($rowUsuarioRealizado['num'] > 0);

        $sqlActividadNueva = "SELECT es_nueva FROM Actividades WHERE id = $actividadId";

        $resActividadNueva = mysqli_query($con, $sqlActividadNueva);

        if (!$resActividadNueva) {
            die("Error en la consulta SQL: " . mysqli_error($con));
        }

        $rowActividadNueva = mysqli_fetch_assoc($resActividadNueva);
        $actividadNueva = ($rowActividadNueva['es_nueva'] == 1);
        
        if ($usuarioHaRealizado) {
            $sqlActualizarEstado = "UPDATE Actividades SET es_nueva = 0 WHERE id = $actividadId";
        } elseif ($actividadNueva) {
            $sqlActualizarEstado = "UPDATE Actividades SET es_nueva = 1 WHERE id = $actividadId";
        }

        if (isset($sqlActualizarEstado)) {
            $resActualizarEstado = mysqli_query($con, $sqlActualizarEstado);

            if (!$resActualizarEstado) {
                die("Error en la consulta SQL de actualización: " . mysqli_error($con));
            }
        }

        date_default_timezone_set('America/Bogota');
        $actividadId = $reg["id"];

        $sqlFechaCreacion = "SELECT fecha_creacion FROM Actividades WHERE id = $actividadId";
        $resFechaCreacion = mysqli_query($con, $sqlFechaCreacion);
        
        if (!$resFechaCreacion) {
            die("Error en la consulta SQL de fecha de creación: " . mysqli_error($con));
        }
        
        $rowFechaCreacion = mysqli_fetch_assoc($resFechaCreacion);
        $fechaCreacion = strtotime($rowFechaCreacion['fecha_creacion']);
        $horaActual = time();
        
        $diferenciaHoras = ($horaActual - $fechaCreacion) / 3600;

        if ($diferenciaHoras >= 8) {
            $sqlActualizarEstado = "UPDATE Actividades SET es_nueva = 0 WHERE id = $actividadId";
        
            $resActualizarEstado = mysqli_query($con, $sqlActualizarEstado);
        
            if (!$resActualizarEstado) {
                die("Error en la consulta SQL de actualización: " . mysqli_error($con));
            }
        }
        
        ?>
        
        <div class="col-md-4">
          <div class="gallery">
            <div class="card">
              <?php if ($usuarioHaRealizado) : ?>
                <span class="ribbon">✔</span>
              <?php endif; ?>
              <?php if ($actividadNueva) : ?>
                <span class="ribbon2">NUEVO</span>
              <?php endif; ?>
              <a href="Respuesta-actividad.php?id=<?php echo $reg["id"];?>&token=<?php echo hash_hmac("sha1", $reg["id"], KEY_TOKEN);?>">
                <img src="<?php echo "../../images/imagenes/".$reg["imagen"]; ?>" class="card-img-top w3-hover-opacity" alt="Imagen actividad">
              </a>
              <div class="card-body">
                <p><b>Fecha de cierre:</b> <span style="color: red;"><?php echo $reg["fecha_vencimiento"]; ?></span></p>
                <h5 class="card-title"><?php echo $reg["nombre"]; ?></h5>
                <p class="card-description"><?php echo substr($reg["descripcion"], 0, 45) . "..."; ?></p>
                <div class="button-container">
                  <a href="Respuesta-actividad.php?id=<?php echo $reg["id"];?>&token=<?php echo hash_hmac("sha1", $reg["id"], KEY_TOKEN);?>" class="btn btn-primary" style="border-color:#1B4F3E;">Realizar actividad</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <?php
      }
      mysqli_close($con);
    ?>
  </div>
</div>
</body>
</html>
