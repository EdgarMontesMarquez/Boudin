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

                // Verificar la respuesta del servidor
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