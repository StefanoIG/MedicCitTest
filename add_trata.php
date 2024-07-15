<?php
session_start();

// Verifica si el usuario está logueado y si tiene permisos para agregar tratamientos
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["tipo_usuario"] !== "admin") {
    header("location: login.php");
    exit;
}

require 'php/config.php'; // Incluye tu script de conexión a la base de datos

$status = ''; // Inicializa una variable para almacenar el estado de la operación

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];

    // Prepara la sentencia de inserción
    $sql = "INSERT INTO especialidad (nombre, tipo, precio, descripcion, estado_especialidad) VALUES (?, ?, ?, ?, 'publicado')";

    if ($stmt = $pdo->prepare($sql)) {
        // Vincula las variables a los parámetros de la sentencia preparada
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $tipo);
        $stmt->bindParam(3, $precio);
        $stmt->bindParam(4, $descripcion);

        // Intenta ejecutar la sentencia preparada
        if ($stmt->execute()) {
            $status = 'success';
        } else {
            $status = 'error';
        }
    } else {
        $status = 'error';
    }
    // Cierra la sentencia
    unset($stmt);
}
// Cierra la conexión
unset($pdo);
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Especialidad - CitasMedicas</title>
    <link rel="stylesheet" href="css/dash.css">
</head>

<body>
    <div class="dashboard-wrapper">
        <nav class="sidebar">
            <h2>New</h2>
            <ul class="nav-links">
                <li><a href="dashboard.php">Estadísticas</a></li>
                <li><a href="calendario.php">Calendario</a></li>
                <li><a href="pacientes.php">Pacientes</a></li>
                <li><a href="admin-trata.php">Lista de Tratamiento-Especialidad</a></li>

                <li><a href="logout.php">Cerrar Sesion</a></li>
            </ul>
        </nav>

        <main class="dashboard-content">
            <h1>Agregar Tratamiento</h1>
            <form action="add_trata.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="tipo">Tipo de tratamiento:</label>
                <select id="tipo" name="tipo" required>
                    <option value="deontologia">Deontología</option>
                    <option value="general">General</option>
                    <option value="pediatria">Pediatría</option>
                </select>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>

                <button type="submit">Agregar Tratamiento</button>
            </form>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verifica el estado y muestra la alerta correspondiente
            var status = <?= json_encode($status); ?>;

            if (status === 'success') {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Tratamiento agregado exitosamente.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Limpia los campos del formulario
                        document.getElementById('nombre').value = '';
                        document.getElementById('tipo').value = '';
                        document.getElementById('nivel').value = '';
                        document.getElementById('descripcion').value = '';
                    }
                });
            } else if (status === 'error') {
                Swal.fire({
                    title: 'Error',
                    text: 'Algo salió mal. Por favor, intenta de nuevo más tarde.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    </script>
</body>

</html>