<?php
session_start();
include 'php/config.php';

// Verificar si el usuario está logueado y tiene permisos necesarios (aquí asumimos que algún tipo de verificación de permisos es necesaria)
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Verificar si se recibió un parámetro 'id' en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Especilidad no encontrada.");
}

$id_especialidad = $_GET['id'];

// Verificar si se recibió una solicitud POST para actualizar la especialidad
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar y sanitizar los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $tipo = htmlspecialchars($_POST['tipo']);
    $estado_especialidad = htmlspecialchars($_POST['estado_especialidad']);
    $precio = floatval($_POST['precio']); // Asumiendo que el precio es un número decimal
    $descripcion = htmlspecialchars($_POST['descripcion']);

    try {
        // Preparar la consulta para actualizar la especialidad
        $sql = "UPDATE especialidad SET nombre = :nombre, tipo = :tipo, estado_especialidad = :estado_especialidad, precio = :precio, descripcion = :descripcion WHERE id_especialidad = :id_especialidad";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'tipo' => $tipo,
            'estado_especialidad' => $estado_especialidad,
            'precio' => $precio,
            'descripcion' => $descripcion,
            'id_especialidad' => $id_especialidad
        ]);

        // Redireccionar a la página de lista de especialidades después de editar
        header("location: admin-trata.php");
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar la especialidad: " . $e->getMessage());
    }
}

// Obtener los detalles actuales de la especialidad para mostrar en el formulario de edición
try {
    $consulta = "SELECT nombre, tipo, estado_especialidad, precio, descripcion FROM especialidad WHERE id_especialidad = :id_especialidad";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute(['id_especialidad' => $id_especialidad]);
    $especialidad = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener la especialidad: " . $e->getMessage());
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
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <main class="dashboard-content">
            <form action="" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($especialidad['nombre']) ?>" required>

                <label for="tipo">Tipo de Especialidad:</label>
                <select id="tipo" name="tipo" required>
                    <option value="deontologica" <?= ($especialidad['tipo'] == 'deontologica') ? 'selected' : '' ?>>Deontológica</option>
                    <option value="pediatra" <?= ($especialidad['tipo'] == 'pediatra') ? 'selected' : '' ?>>Pediatría</option>
                    <option value="general" <?= ($especialidad['tipo'] == 'general') ? 'selected' : '' ?>>General</option>
                </select>

                <label for="estado_especialidad">Estado de Especialidad:</label>
                <select id="estado_especialidad" name="estado_especialidad" required>
                    <option value="publicado" <?= ($especialidad['estado_especialidad'] == 'publicado') ? 'selected' : '' ?>>Publicado</option>
                    <option value="cancelado" <?= ($especialidad['estado_especialidad'] == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
                </select>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?= $especialidad['precio'] ?>" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($especialidad['descripcion']) ?></textarea>

                <button type="submit">Actualizar Especialidad</button>
            </form>
        </main>
    </div>
</body>

</html>