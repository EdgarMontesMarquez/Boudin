<?php
session_start();

// Verificar si es una solicitud POST válida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID de respuesta desde el formulario
    if (isset($_POST['id_respuesta'])) {
        $id_respuesta = $_POST['id_respuesta'];

        // Simular la verificación de si el usuario ya ha dado Me Gusta antes
        // Aquí debes adaptarlo según tu estructura y lógica de base de datos
        $UserID = $_SESSION['UserID']; // Obtener el ID del usuario desde la sesión
        $hasLiked = false; // Variable para verificar si el usuario ya ha dado Me Gusta
        
        // Conectar a la base de datos (asumiendo que ya tienes la conexión configurada)
        require_once '../../configuraciones/conexion.php';

        // Verificar si el usuario ya ha dado Me Gusta a esta respuesta
        $queryCheckLiked = "SELECT COUNT(*) AS count_likes FROM megusta_respuestas WHERE id_usuario = ? AND id_respuesta = ?";
        $stmtCheckLiked = $con->prepare($queryCheckLiked);
        $stmtCheckLiked->bind_param("ii", $UserID, $id_respuesta);
        $stmtCheckLiked->execute();
        $resultCheckLiked = $stmtCheckLiked->get_result();
        $rowCheckLiked = $resultCheckLiked->fetch_assoc();

        if ($rowCheckLiked['count_likes'] > 0) {
            // El usuario ya ha dado Me Gusta antes
            $response = array('success' => false, 'error' => 'Ya has dado Me Gusta a esta respuesta');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Si el usuario no ha dado Me Gusta antes, proceder a insertar el like en la base de datos
        $queryInsertLike = "INSERT INTO megusta_respuestas (id_usuario, id_respuesta) VALUES (?, ?)";
        $stmtInsertLike = $con->prepare($queryInsertLike);
        $stmtInsertLike->bind_param("ii", $UserID, $id_respuesta);

        if ($stmtInsertLike->execute()) {
            // Si se insertó correctamente, obtener el número actualizado de likes para la respuesta
            $queryGetNumLikes = "SELECT COUNT(*) AS num_likes FROM megusta_respuestas WHERE id_respuesta = ?";
            $stmtGetNumLikes = $con->prepare($queryGetNumLikes);
            $stmtGetNumLikes->bind_param("i", $id_respuesta);
            $stmtGetNumLikes->execute();
            $resultNumLikes = $stmtGetNumLikes->get_result();
            $num_likes = $resultNumLikes->fetch_assoc()['num_likes'];

            // Preparar la respuesta JSON con el número actualizado de likes
            $response = array('success' => true, 'num_likes' => $num_likes);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // Si hubo un error al insertar el like
            $response = array('success' => false, 'error' => 'Error al guardar Me Gusta. Inténtalo de nuevo más tarde.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
}

// Si no es una solicitud POST válida o falta el ID de respuesta
$response = array('success' => false, 'error' => 'Invalid request');
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
