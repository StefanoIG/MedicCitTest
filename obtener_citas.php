<?php
session_start();
include 'php/config.php';

// Verificar si se recibió la fecha por GET
if (!isset($_GET['fecha'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Fecha no proporcionada'));
    exit;
}

$fecha = $_GET['fecha'];

// Obtener citas para el día dado con el nombre de usuario
$consulta = "SELECT c.id_cita, c.fecha_hora_cita, u.nombres
             FROM cita c
             INNER JOIN usuario u ON c.id_usuario = u.id_usuario
             WHERE DATE(c.fecha_hora_cita) = :fecha";

try {
    $stmt = $pdo->prepare($consulta);
    $stmt->execute(['fecha' => $fecha]);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver citas en formato JSON
    header('Content-Type: application/json');
    echo json_encode($citas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array('error' => 'Error al obtener citas: ' . $e->getMessage()));
    exit;
}
