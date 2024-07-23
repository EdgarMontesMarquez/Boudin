<?php
session_start();
require_once '../../configuraciones/conexion.php'; //conexion a la base de datos

//obtener los datos del formulario
$nombre = mysqli_real_escape_string($con,$_POST['nombre']);
$descripcion = mysqli_real_escape_string($con,$_POST['descripcion']);
$fechaVencimiento = mysqli_real_escape_string($con, $_POST['fecha_vencimiento']); 

//validar los datos(ejemplo: verificar si los campos requeridos estan presentes)
if(!empty($nombre)&& !empty($descripcion)&& !empty($fechaVencimiento)){
    //ahora subimos la imagen, para eso se debe crear una carpeta para guardar esas imagenes tanto de manera local como 
    //en la base de datos
    $imagen = $_FILES['imagen'];// aqui se obtiene el archivo que se ha subido al formulario
    $imagen_nombre = $imagen['name']; //aqui se extrae el nombre original del archivo
    $imagen_temporal = $imagen['tmp_name']; //obtenemos la ubicacion temporal del archivo
    $imagen_ruta = '../../images/imagenes/'. $imagen_nombre; // se especifica la ruta en la que se guardara el archivo en el servidor
    move_uploaded_file($imagen_temporal, $imagen_ruta);// esta funcion mueve el archivo de su ubicacion temporal a la ubicacion final especificada en $imagen_ruta.

    
// Obtener la fecha de creación actual
date_default_timezone_set('America/Bogota');
$fechaCreacion = date('Y-m-d H:i:s'); // Formato: Año-Mes-Día Hora:Minuto:Segundo

//realizamos la inserción en la base de datos
$sql = "INSERT INTO actividades (nombre, descripcion,imagen,fecha_creacion, fecha_vencimiento,es_nueva) VALUES ('$nombre','$descripcion','$imagen_nombre','$fechaCreacion', '$fechaVencimiento','1')";
$resultado = mysqli_query($con,$sql);

if($resultado){
    //vamos a mostrar este mensaje de exito o error en la vista mi formulario 
    $_SESSION['error']='Actividad creada exitosamente';
    header('Location:../../vistas/administrador/Actividades.php');
    exit();
}else{
    echo "Error al crear el producto:". mysqli_error($con);
}
}else{
    echo "Por favor, complete todos los campos del formulario correctamente.";
}
mysqli_close($con);
?>