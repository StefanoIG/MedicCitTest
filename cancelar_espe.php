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
    die("Error: Especialidad no encontrada.");
}

$id_especialidad = $_GET['id'];

try {
    // Preparar la consulta para actualizar el estado de la especialidad a 'cancelado'
    $sql = "UPDATE especialidad SET estado_especialidad = 'cancelado' WHERE id_especialidad = :id_especialidad";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_especialidad' => $id_especialidad]);

    // Redireccionar a la página de lista de especialidades después de cancelar
    header("location: admin-trata.php");
    exit;
} catch (PDOException $e) {
    die("Error al cancelar la especialidad: " . $e->getMessage());
}
