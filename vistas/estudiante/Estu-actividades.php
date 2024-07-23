<?php
session_start();
if (!isset($_SESSION['UserID'])) {

  header('Location: ../principal/index.php');

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="icon" type="image/png" href="../../images/captura.png">


</head>
<body>
<?php

    require_once "Header_estudiante.php";
    require_once "cards.php";
?>
<br><br>

<div style="background-color:#e5e5e5;text-align:center;padding:10px;margin-top:7px;">&copy; Boudin. Todos los derechos reservados.</div>

</body>
</html>