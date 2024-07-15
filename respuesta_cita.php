<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Respuesta de Reserva de Cita - DentalCare</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que el enlace al CSS es correcto -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <!-- Aquí puedes incluir más elementos HTML si lo deseas -->
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const mensaje = params.get('mensaje');

        Swal.fire({
            title: 'Información de la Cita',
            text: decodeURIComponent(mensaje.replace(/\+/g, ' ')),
            icon: 'info',
            confirmButtonText: 'Ok'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>
</body>

</html>