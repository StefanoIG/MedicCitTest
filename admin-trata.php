<?php
session_start();
include 'php/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$pacientes = [];
try {
    $consulta = "SELECT * from especialidad";
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
    <title>Pacientes - DentalCare</title>
    <link rel="stylesheet" href="css/dash.css">
</head>

<body>
    <div class="dashboard-wrapper">
        <nav class="sidebar">
            <h2>Cita medica</h2>
            <ul class="nav-links">
                <li><a href="dashboard.php">Estadísticas</a></li>
                <li><a href="calendario.php">Calendario</a></li>
                <li><a href="pacientes.php">Pacientes</a></li>
                <li><a href="admin-trata.php">Lista de Tratamiento-Especialidad</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <main class="dashboard-content">
            <h1>Lista de Especialidades</h1>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo de Tratamiento-Especialidad</th>
                        <th>Descripcion</th>
                        <th>precio</th>
                        <th>Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente) : ?>
                        <tr>
                            <td><?= htmlspecialchars($paciente['nombre']) ?></td>
                            <td><?= htmlspecialchars($paciente['tipo']) ?></td>
                            <td><?= htmlspecialchars($paciente['descripcion']) ?></td>
                            <td><?= htmlspecialchars($paciente['precio']) ?></td>
                            <td><?= htmlspecialchars($paciente['estado_especialidad']) ?></td>

                            <td>
                                <a href="editar_especialidad.php?id=<?= $paciente['id_especialidad'] ?>">Editar</a> |
                                <a href="cancelar_espe.php?id=<?= $paciente['id_especialidad'] ?>">Cancelar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>