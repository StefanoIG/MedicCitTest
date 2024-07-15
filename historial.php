<?php
session_start();
include 'php/config.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$idUsuario = $_SESSION["id_usuario"];

// Consulta para obtener el historial del usuario
try {
    $consultaHistorial = "SELECT * FROM Cita WHERE id_usuario = ? ORDER BY fecha_hora_cita DESC";
    $stmt = $pdo->prepare($consultaHistorial);
    $stmt->execute([$idUsuario]);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ERROR: No se pudo ejecutar $consultaHistorial. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - DentalCare</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
    table {
        margin-left: 100px;
        width: 80%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table thead th {
        background-color: #007bff;
        color: white;
        text-align: left;
        padding: 8px;
    }

    main h1 {
        text-align: center;
        color: #333;
    }

    table tbody td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table tbody tr:hover {
        background-color: #e9ecef;
    }
</style>

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

                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) : ?>
                    <!-- Opciones para usuarios logueados -->
                    <li><a href="historial.php">Historial</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else : ?>
                    <!-- Opciones para usuarios no logueados -->
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <main>
            <h1>Historial de Citas</h1>
            <table>
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Tipo de Cita</th>
                        <th>Estado</th>
                        <!-- Agrega más columnas si lo necesitas -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $cita) : ?>
                        <tr>
                            <td><?= htmlspecialchars($cita['fecha_hora_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['tipo_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['estado_cita']) ?></td>
                            <!-- Más datos de cada cita si es necesario -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
</body>

</html>