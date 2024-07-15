<?php
session_start();
include 'php/config.php';

$loginStatus = "";
$errorMessage = "";
$redirectUrl = ""; // Inicializa la variable aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT id_usuario, nombre, tipo_usuario, contrasena FROM usuario WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            if (password_verify($password, $row['contrasena'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id_usuario"] = $row['id_usuario'];
                $_SESSION["nombre"] = $row['nombre'];
                $_SESSION["tipo_usuario"] = $row['tipo_usuario'];

                $loginStatus = "success";
                $redirectUrl = $_SESSION["tipo_usuario"] == "admin" ? "dashboard.php" : "tratamientos.php";
            } else {
                $loginStatus = "fail";
                $errorMessage = "Contraseña incorrecta.";
            }
        } else {
            $loginStatus = "fail";
            $errorMessage = "No se encontró cuenta con ese correo electrónico.";
        }
    } catch (PDOException $e) {
        $loginStatus = "fail";
        $errorMessage = "ERROR: No se pudo ejecutar la consulta. " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DentalCare</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header class="main-header">
        <nav class="main-nav">
            <div class="nav-brand">
                <a href="index.php">Dental<strong>Care</strong></a>
            </div>
            <ul>
                <li><a href="index.php#inicio">Inicio</a></li>
                <li><a href="index.php#nosotros">Así somos</a></li>
                <li><a href="index.php#servicios">Tratamientos</a></li>
                <li><a href="#footer">Contacto</a></li>
                <li><a href="register.php">Registrarse</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="conta" style="background-image: url('img/fondo.jpg');">
            <div class="register-container">
                <h2>Iniciar Sesión</h2>
                <?php if ($loginStatus === "fail") : ?>
                    <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
                <?php endif; ?>
                <form class="register-form" action="login.php" method="POST">
                    <div class="form-group">
                        <label for="login-email">Email:</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Contraseña:</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <div class="form-group login-link">
                        <p>¿No tienes cuenta? <a href="register.php" class="strong-link">Regístrate</a></p>
                    </div>
                    <div class="form-group login-link">
                        <p id='Recuperar_pass'> Recuperar password</p>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            var loginStatus = "<?php echo htmlspecialchars($loginStatus); ?>";

            if (loginStatus === "success") {
                Swal.fire({
                    title: 'Iniciando sesión',
                    text: 'Por favor, espere...',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                }).then(() => {
                    window.location.href = "<?php echo htmlspecialchars($redirectUrl); ?>";
                });
            } else if (loginStatus === "fail") {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error, revisa las credenciales',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            }
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('Recuperar_pass').addEventListener('click', function() {
                Swal.fire({
                        title: 'Recuperar Contraseña',
                        html: '<input type="email" id="correo" class="swal2-input" placeholder="Correo electrónico">',
                        showCancelButton: true,
                        confirmButtonText: 'Recuperar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        preConfirm: (correo) => {
                            return fetch('recuperar_contrasena.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'correo=' + correo,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (!data.success) {
                                        throw new Error(data.message);
                                    }
                                    return data;
                                })
                                .catch(error => {
                                    Swal.showValidationMessage(error.message);
                                });
                        },
                    })
                    .then((result) => {
                        if (result.value) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: result.value.message,
                            });
                        }
                    });
            });
        });
    </script>


</body>

</html>