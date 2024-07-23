<?php

    require_once '../../configuraciones/conexion.php';
    require_once '../../configuraciones/config.php';
    //se obtiene el id del producto y se genera un token de acuerdo al id, teniendo en cuenta que se esta cifrando la informacion del producto

    // solicitamos que se obtenga un valor que se llama id y token, esto viene del metodo get ya que es por url
    //validamos con isset() para ver si esta definido el id y tambien para el token, que si esta definido que lo reciva si no que le de un valor vacio o predefinido para que no genere error

    $id=isset($_GET["id"]) ? $_GET["id"]: "";
    $token=isset($_GET["token"]) ? $_GET["token"]: "";

    //validamos ambos datos, mientras que id este vacio y token tambien es que no estamos enviando datos, solo solicitamos la url sin enviar la información
    if($id == "" || $token == ""){
        echo "Error al procesar la petición";
        exit;
    }else{
    //procesamos el token si se esta enviando los datos de forma correcta
    // se procesa el token y el id que se esta reciviendo
        $token_tmp = hash_hmac("sha1", $id, KEY_TOKEN);
    //validamos si el token que  el usuario envia es igual al que yo genero, si no es igual se envia un error
        if($token == $token_tmp){
    //hacemos las consultas de este producto
            $sql =$con->prepare("SELECT count(id) FROM actividades WHERE id=?");
            $sql->bind_param("i", $id);
            $sql->execute();
            $result = $sql->get_result();
            $row = $result->fetch_assoc();
            if ($row['count(id)'] > 0) {
                $sql = $con->prepare("SELECT imagen, nombre, descripcion, fecha_vencimiento FROM actividades WHERE id=?");
                $sql->bind_param("i", $id);
                $sql->execute();
                $result = $sql->get_result();
                $res = $result->fetch_assoc();
                $imagen = $res["imagen"];
                $nombre = $res["nombre"];
                $descripcion = $res["descripcion"];
                $fecha_vencimiento = $res["fecha_vencimiento"];
            }

        } else {
            echo "Error al procesar la petición";
            exit;
        }
    }

?>