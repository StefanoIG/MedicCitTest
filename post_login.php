<?php
session_start();

// Verificar si se ha establecido un estado de inicio de sesión
if (!isset($_SESSION["login_status"])) {
    header("location: login.php");
    exit;
}

$loginStatus = $_SESSION["login_status"];
$errorMessage = $_SESSION["error_message"] ?? ''; // Mensaje de error si existe
unset($_SESSION["login_status"], $_SESSION["error_message"]);

$redirectPage = $_SESSION["tipo_usuario"] == "admin" ? "dashboard.php" : "index.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        window.onload = function() {
            if ('<?php echo $loginStatus; ?>' === "success") {
                alertaIniciandoSesion();
            } else {
                alertaCredencialesIncorrectas('<?php echo $errorMessage; ?>');
            }

            setTimeout(function() {
                window.location.href = '<?php echo $redirectPage; ?>';
            }, 1);
        };

        function alertaIniciandoSesion() {
            Swal.fire({
                title: 'Iniciando sesión',
                text: 'Por favor, espere...',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }

        function alertaCredencialesIncorrectas(errorMessage) {
            Swal.fire({
                title: 'Error',
                text: errorMessage,
                icon: 'error',
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }
    </script>
</body>

</html>