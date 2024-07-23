<?php
    require_once '../../controladores/estudiante/mostrar-actividad.php';
    session_start();
    if (!isset($_SESSION['UserID'])) {

    header('Location: ../login/loginYregistro.php');

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boudin | Estudiante</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
    <link rel="stylesheet" href="../../assets/css/modal1.css"/>
    <link rel="stylesheet" href="../../assets/css/form.css"/>
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">x
    <style>
div.noticia {
  margin: 20px auto;
  padding: 15px;
}

div.noticia img.izquierda {
  float: left;
  margin-right: 15px;
}

div.reset {
  clear: both;
}
  </style>
</head>
<body class="body-color">
    <?php
        require_once 'Header_estudiante.php';   
    ?>
<br>
<!-- detalles de la actividad -->

<div class="container">
<h2 class="titulo"><?php echo $nombre; ?></h2>

  <div class="respo noticia">
     <img src="<?php echo "../../images/imagenes/".$imagen; ?>" class="w3-round izquierda" alt="Product Image" style="max-width:500px;height:auto; ">
   
     <aside id="texto"><?php echo $descripcion; ?></aside>
     <div class="reset"></div>
  </div><br>
  <div class="respo" >
        <div class="card">
            <h1 align="center">Responder Actividad</h1><br>
        
            <strong><p>Fecha de cierre: </strong> <span style="color: red;"><?php echo  $fecha_vencimiento; ?></span></p>
            <form action="../../controladores/estudiante/responder-actividad.php" id="formularioRespuesta" method="post" enctype="multipart/form-data">

                <div class="prueba">
                <div class="form-group">
                    <label for="respuesta_textual">Respuesta de actividad:</label>
                    <textarea name="respuesta_textual"   class="form-control" rows="8" required></textarea>
                </div>
                <div class="form-group ">
                <label for="imagen">Adjuntar Imagen (opcional):</label>
                <input type="file" class="form-control" name="imagen" id="imagen" > </div>
            </div>
                <input type="hidden" name="id_actividad" value="<?php echo $id; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
        
                <button type="submit" class="btn btn-primary btn-block" id="enviarRespuesta">Enviar Respuesta</button>
            </form>
            
        </div>
        </div> 
    </div><br>
<div class="loader"></div>
<div style="background-color:#e5e5e5;text-align:center;padding:10px;margin-top:7px;">&copy; Boudin. Todos los derechos reservados.</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../../assets/js/modal.js"></script>



<script>
$(document).ready(function () {
    $('#enviarRespuesta').on('click', function (e) {
        e.preventDefault();

        var formData = new FormData($('#formularioRespuesta')[0]);

        $.ajax({
            type: 'POST',
            url: $('#formularioRespuesta').attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                var icon;
                var message;

                // Verificar la respuesta del servir
                if (response === "Ya has realizado esta actividad anteriormente.") {
                    icon = 'info';
                    message = response;
                } else if (response === "Respuesta enviada con éxito.") {
                    icon = 'success';
                    message = response;
                } else {
                    icon = 'error';
                    message = 'Hubo un problema al procesar tu solicitud. Inténtalo de nuevo más tarde.';
                }

                Swal.fire({
                    icon: icon,
                    title: message,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    if (icon === icon) {
                        window.location.href = 'Estu-actividades.php';
                    }
                });
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al procesar tu solicitud. Inténtalo de nuevo más tarde.',
                    showConfirmButton: true,
                });
            }
        });
    });
});

</script>



</body>
</html>
