<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$pacientes = [];
try {
    $consulta = "SELECT CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo, u.movil, 
                 (SELECT t.nombre FROM especialidad t 
                  JOIN cita ci ON t.id_especialidad = ci.id_especialidad 
                  JOIN compra c ON ci.id_cita = c.id_cita
                  JOIN modos_pago mp ON c.id_pago = mp.id_modo
                  WHERE mp.id_usuario = u.id_usuario 
                  ORDER BY c.fecha_compra DESC LIMIT 1) AS ultimo_especialidad 
                 FROM usuario u";
    $stmt = $pdo->query($consulta);
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pacientes[] = $fila;
    }
} catch (PDOException $e) {
    die("ERROR: No se pudo ejecutar $consulta. " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - CitaMedica</title>
    <link rel="stylesheet" href="css/dash.css">
</head>
<body>
<div class="dashboard-wrapper">
    <nav class="sidebar">
        <h2>CitaMedica</h2>
        <ul class="nav-links">
            <li><a href="dashboard.php">Estadísticas</a></li>
            <li><a href="calendario.php">Calendario</a></li>
            <li><a href="pacientes.php">Pacientes</a></li>
            <li><a href="admin-trata.php">Lista de Tratamiento-Especialidad</a></li>
            <li><a href="logout.php">Cerrar Sesion</a></li>
        </ul>
    </nav>
    <main class="dashboard-content">

        <h1>Lista de Pacientes</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Número de Celular</th>
                    <th>Último especialidad Realizado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                    <td><?= htmlspecialchars($paciente['nombre_completo']) ?></td>
                        <td><?= htmlspecialchars($paciente['movil']) ?></td>
                        <td><?= $paciente['ultimo_especialidad'] ? htmlspecialchars($paciente['ultimo_especialidad']) : "Nunca ha tomado un especialidad" ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
