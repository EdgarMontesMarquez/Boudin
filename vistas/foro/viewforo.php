
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro de Discusión</title>
    <!-- Bootstrap CSS -->
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px; /* Aumentado el ancho del contenedor */
            margin: auto;
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 12px; /* Añadido borde redondeado */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4); /* Sombra suave */
        }

        .card-header {
            background-color: #1B4F3E; /* Color predominante */
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 12px 12px 12px 12px; /* Borde redondeado solo arriba */
            padding: 15px; /* Aumento de espacio interno */
        }

        .card-body {
            background-color: #fff;
            border-radius: 12px 12px 12px 12px; /* Borde redondeado solo abajo */
            padding: 20px; /* Aumento de espacio interno */
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-subtitle {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between; /* Alinear elementos a los lados */
            align-items: center; /* Alinear verticalmente */
        }

        .card-text {
            color: #333;
            margin-bottom: 10px;
        }

        .responses {
            color: #1B4F3E; /* Color para el número de respuestas */
            font-weight: bold;
            font-size: 10px; /* Tamaño del contador de respuestas */
            margin-bottom: 0; /* Eliminar margen inferior */
        }

        .btn-responder {
            background-color: #1B4F3E; /* Color para el botón Responder */
            color: white;
            padding: 10px 20px; /* Aumento del padding para hacer el botón más largo */
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 80%; /* Ancho del 80% del contenedor padre */
            max-width: 300px; /* Máximo ancho para evitar que ocupe toda la pantalla en dispositivos pequeños */
            text-align: center;
            display: flex; /* Añadido display flex para centrar ícono y contador */
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            border-radius: 4px; /* Borde redondeado */
            border-width: 1px; /* Grosor del borde */
            border-color: #1B4F3E; /* Color del borde */
        }

        .btn-responderr {
            background-color: #1B4F3E; /* Color para el botón Responder */
            color: white;
            padding: 10px 20px; /* Aumento del padding para hacer el botón más largo */
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%; /* Ancho del 80% del contenedor padre */
            max-width: 900px; /* Máximo ancho para evitar que ocupe toda la pantalla en dispositivos pequeños */
            text-align: center;
            display: flex; /* Añadido display flex para centrar ícono y contador */
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            border-radius: 4px; /* Borde redondeado */
            border-width: 1px; /* Grosor del borde */
            border-color: #1B4F3E; /* Color del borde */
        }

        .btn-responder:hover {
            background-color: #1B4F3E; /* Color de hover para el botón Responder */
        }

        .btn-responder i {
            margin-right: 5px; /* Espacio entre el ícono y el texto del botón */
            font-size: 24px; /* Tamaño del ícono */
        }

        .btn-responderr i {
            margin-right: 5px; /* Espacio entre el ícono y el texto del botón */
            font-size: 24px; /* Tamaño del ícono */
        }

        .footer {
            background-color: #e5e5e5;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        /* Estilos para el Buscador */
        .search-form {
            margin-bottom: 18px;
        }

        .search-input {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-button {
            background-color: #1B4F3E; /* Color para el botón Buscar */
            color: white;
            border: none;
            padding: 7px 10px;
            margin-left: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .search-button:hover {
            background-color: #1B4F3E; /* Color de hover para el botón Buscar */
        }
        
    </style>
    
</head>
<body>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">Foro de Discusión</div>
            <div class="card-body">
                <!-- Formulario de Búsqueda -->
                <form class="search-form" action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control search-input" name="search" placeholder="Buscar temas por título o descripción..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary search-button" type="submit"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </form>

                <a href="../foro/formulario_crear_tema.php" class="btn  mb-3 btn-responder"><i class="fa fa-plus-circle"></i> Crear Nuevo Tema</a>


                <?php foreach ($temas as $tema) { ?>
                    <div class="card mb-3">
                        <div class="card-body d-flex">
                        <div class="card-left flex-grow-1 pr-3">
                        <div class="d-flex align-items-center mb-3">
                            <?php
                            // Obtener la inicial del nombre del creador
                            $inicial = strtoupper(substr($tema['NombreCreador'], 0, 1));
                            // URL de la imagen predeterminada con la inicial
                            $imagen_predeterminada = "https://via.placeholder.com/40?text=$inicial"; // Imagen de ejemplo con Placeholder.com
                            ?>
                            <img src="<?php echo $imagen_predeterminada; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                            <div>
                                <h5 class="card-title"><?php echo htmlspecialchars($tema['titulo']); ?></h5>
                                <div class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($tema['NombreCreador']); ?> | <?php echo date('d/m/Y', strtotime($tema['fecha_creacion'])); ?></div>
                            </div>
                        </div>
                        <p class="card-text" style="margin-left: 50px;"><?php echo htmlspecialchars(substr($tema['descripcion'], 0, 45)." ..."); ?></p>
                    </div>

                            <div class="card-right">
                            <div class="d-flex flex-column align-items-center">
                                <div class="responses mb-2">
                                    <i class="fa fa-comments fa-3x" style="font-size: 24px;"></i> <!-- Mantienes el tamaño del ícono -->
                                    <span style="font-size: 24px;"><?php echo htmlspecialchars($tema['num_respuestas']); ?></span> <!-- Ajustas el tamaño del número -->
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center justify-content-md-end">
                            <a href="../foro/responder_tema.php?id=<?php echo $tema['id']; ?>" class="btn btn-responder"><i class="fa fa-reply"></i> Responder</a>
                        </div>

                    </div>
                <?php } ?>

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
</body>
</html>
