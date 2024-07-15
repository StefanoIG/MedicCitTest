<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Incluye el archivo de configuración de la base de datos aquí
require 'php/config.php'; // Cambia la ruta si es necesario para apuntar a tu archivo de configuración real

// Asegúrate de tener la conexión a la base de datos configurada en $pdo
$consultaUsuarios = "SELECT COUNT(*) AS totalUsuarios FROM usuario";
// Consultas a la base de datos
$consultaCitasProgramadas = "SELECT COUNT(*) AS totalCitas FROM Cita WHERE estado_cita = 'programada'";
$consultaTratamientosCompletados = "SELECT COUNT(*) AS totalCompletados FROM cita WHERE estado_cita = 'completado'"; // Asumiendo que hay una columna 'estado'
$consultaTratamientosDisponibles = "SELECT COUNT(*) AS totalDisponibles FROM especialidad WHERE estado_especialidad = 'publicado'";
$consultaCitasMes = "SELECT COUNT(*) AS totalCitasMes FROM Cita WHERE MONTH(fecha_hora_cita) = MONTH(CURRENT_DATE()) AND YEAR(fecha_hora_cita) = YEAR(CURRENT_DATE())";

// Ejecución de consultas y almacenamiento de resultados
$totalCitas = $pdo->query($consultaCitasProgramadas)->fetchColumn();
$totalCompletados = $pdo->query($consultaTratamientosCompletados)->fetchColumn();
$totalDisponibles = $pdo->query($consultaTratamientosDisponibles)->fetchColumn();
$totalCitasMes = $pdo->query($consultaCitasMes)->fetchColumn();

$stmt = $pdo->prepare($consultaUsuarios);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalUsuarios = $row['totalUsuarios'];
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CitaMedica</title>
    <link rel="stylesheet" href="css/dash.css">
</head>

<body>

    <div class="dashboard-wrapper">
        <nav class="sidebar">
            <h2>Citas medicas</h2>
            <ul class="nav-links">
                <li><a href="dashboard.php">Estadísticas</a></li>
                <li><a href="calendario.php">Calendario</a></li>
                <li><a href="pacientes.php">Pacientes</a></li>
                <li><a href="admin-trata.php">Lista de Tratamiento-Especialidad</a></li>

                <li><a href="logout.php">Cerrar Sesion</a></li>
            </ul>
        </nav>
        <main class="dashboard-content">
            <section class="stats">


                <div class="stat-card">
                    <h3>Tratamientos Completados</h3>
                    <p class="stat-number"><?= htmlspecialchars($totalCompletados) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Especialidad Disponibles</h3>
                    <p class="stat-number"><?= htmlspecialchars($totalDisponibles) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Citas para este mes</h3>
                    <p class="stat-number"><?= htmlspecialchars($totalCitasMes) ?></p>
                </div>

                <div class="stat-card">
                    <h3>Total de usuarios registrados</h3>
                    <p class="stat-number"><?php echo htmlspecialchars($totalUsuarios); ?></p>
                </div>

                <!-- Más tarjetas de estadísticas según sea necesario -->
            </section>
            <div class="button-container">
                <a href="add_trata.php" class="button">Agregar Especialidad</a>
                <a href="pacientes.php" class="button">Lista de Pacientes</a>
                <a href="calendario.php" class="button">Calendario</a>
            </div>

        </main>
    </div>

</body>

</html>