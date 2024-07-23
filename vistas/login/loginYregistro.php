<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css" />
    <title>Boudin</title>
    <link rel="icon" type="image/png" href="../../images/captura.png">
    <style>
        /* Estilos adicionales para el contenedor de la contraseña */
        .password-input {
            position: relative;
            display: inline-block;
            width: 100%;
            background-color: transparent;
            border-bottom: 1px solid #ccc;
        }

        .password-input input {
            width: calc(100% - 0px);
            padding-right: 30px;
        }

        .password-input i {
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #1B4F3E;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .password-input i:hover {
            color: #ff6347; /* Cambia el color cuando se pasa el cursor por encima */
            transform: translateY(-50%) scale(1.2); /* Aumenta el tamaño del icono */
        }

        /* Animación para cambiar el icono */
        .password-input i.fa-eye-slash {
            color: #ff6347;
        }

        .password-input i.fa-eye {
            color: #1B4F3E;
        }
    </style>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <br>
            <form id="registroForm" action="../../controladores/login/registro.php" method="POST">
                <h1>Crear una cuenta</h1>
                <div id="mensajeRegistro" style="color: #1B4F3E; font-size: 14px;"></div>
                <input type="number" name="num_dni" placeholder="Número de identificación" required>
                <input type="text" name="nombre_usuario" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <div class="password-input">
                    <input type="password" name="password" id="passwordRegistro" placeholder="Crea una contraseña"
                        required>
                    <i class="far fa-eye" onclick="togglePasswordVisibility('passwordRegistro')"></i>
                </div>
                <div class="password-input">
                    <input type="password" name="confirmar_password" id="passwordRegistro2"
                        placeholder="Confirma tu contraseña" required>
                    <i class="far fa-eye" onclick="togglePasswordVisibility('passwordRegistro2')"></i>
                </div>
                <button type="submit">Registrarme</button>
                <button type="button" onclick="limpiarFormulario()">Limpiar datos</button>
                <button type="button" id="login2" class="ocultar" style="cursor: pointer;">Iniciar sesión</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <br>
            <a href="../principal/index.php" style="padding: 30px; color: #000;">Volver a inicio</a>
            <form id="loginForm" action="../../controladores/login/login.php" method="post">
                <h1>Iniciar sesión</h1>
                <br>
                <input type="email" name="email" placeholder="Correo Electrónico">
                <div class="password-input">
                    <input type="password" name="password" id="passwordLogin" placeholder="Contraseña">
                    <i class="far fa-eye" onclick="togglePasswordVisibility('passwordLogin')"></i>
                </div>
                <div id="mensajeLogin" style="color: red; font-size: 12px;"></div>
                <button type="submit">Iniciar sesión</button>
                <button type="button" class="ocultar" id="register2">Registrarme</button><br>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Ingrese sus datos personales para utilizar todas las funciones del sitio</p>
                    <button class="hidden" id="login">Iniciar sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, amigo!</h1>
                    <p>Regístrese con sus datos personales para utilizar todas las funciones del sitio</p>
                    <button class="hidden" class="ocultar" id="register">Registrarme</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Seleccionar el formulario y el div de mensajes
            var registroForm = document.getElementById('registroForm');
            var mensajeRegistro = document.getElementById('mensajeRegistro');

            // Manejar el evento de envío del formulario
            registroForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevenir el envío normal del formulario

                // Realizar la solicitud AJAX para procesar el formulario
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../../controladores/login/registro.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Actualizar el contenido del div de mensajes con la respuesta del servidor
                        mensajeRegistro.innerHTML = xhr.responseText;
                    }
                };

                // Enviar los datos del formulario
                var formData = new FormData(registroForm);
                xhr.send(new URLSearchParams(formData));
            });

            var loginForm = document.getElementById('loginForm');
            var mensajeLogin = document.getElementById('mensajeLogin');

            loginForm.addEventListener('submit', function (event) {
                event.preventDefault();

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../../controladores/login/login.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Procesar la respuesta JSON
                        var response = JSON.parse(xhr.responseText);

                        if (response.redirect) {
                            // Redirigir si hay información de redirección
                            window.location.href = response.redirect;
                        } else if (response.error) {
                            // Mostrar mensaje de error si hay un error
                            mensajeLogin.innerHTML = response.error;
                        }
                    }
                };

                var formData = new FormData(loginForm);
                xhr.send(new URLSearchParams(formData));
            });
        });

        function limpiarFormulario() {
            // Obtener el formulario por su ID
            var formulario = document.getElementById("registroForm");

            // Restablecer los valores de los campos del formulario
            formulario.reset();

            // Eliminar el contenido del div que contiene el mensaje
            var mensajeRegistro = document.getElementById("mensajeRegistro");
            if (mensajeRegistro) {
                mensajeRegistro.innerHTML = ''; // Elimina el contenido del div
            }
        }

        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            var icon = input.nextElementSibling;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
