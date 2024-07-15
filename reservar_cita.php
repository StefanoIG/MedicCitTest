<?php
session_start();
include 'php/config.php'; // Incluye la conexión a la base de datos

// Verifica si el usuario está logueado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Recibe los datos de la cita y tratamiento desde el formulario
$idTratamiento = $_POST['id_especialidad'] ?? null;
$fechaCita = $_POST['fecha_cita'] ?? null;
$horaCita = $_POST['hora_cita'] ?? null;
$idUsuario = $_SESSION["id_usuario"]; // Asume que el ID del usuario está guardado en la sesión

// Convierte la fecha y hora en un formato adecuado para MySQL
$fechaHoraCita = $fechaCita . ' ' . $horaCita . ':00';

try {
    // Inicia una transacción
    $pdo->beginTransaction();

    // Inserta en la tabla Factura
    $stmtFactura = $pdo->prepare("INSERT INTO compra (id_usuario, fecha_emision, subtotal, estado_de_pago, nota) VALUES (?, NOW(), ?, 'pendiente', '')");
    $stmtFactura->execute([$idUsuario, 0]); // Aquí debes calcular el subtotal adecuadamente
    $idFactura = $pdo->lastInsertId();

    // Inserta en la tabla Cita
    $stmtCita = $pdo->prepare("INSERT INTO Cita (id_usuario, tipo_cita, estado_cita, fecha_hora_cita, fecha_cita) VALUES (?, ?, ?, ?, ?)");
    $stmtCita->execute([$idUsuario, 'tipo_de_cita', 'pendiente', $fechaHoraCita, $fechaCita]);

    // Inserta en la tabla Detalle_Factura
    $stmtDetalle = $pdo->prepare("INSERT INTO Detalle_Factura (id_factura, id_tratamiento, cantidad, precio, descuento) VALUES (?, ?, ?, ?, ?)");
    $stmtDetalle->execute([$idFactura, $idTratamiento, 1, 'precio_tratamiento', 0]); // Debes obtener el precio del tratamiento

    // Confirma la transacción
    $pdo->commit();

    header("Location: respuesta_cita.php?mensaje=Cita+reservada+con+éxito");
    exit;
} catch (PDOException $e) {
    // En caso de error, revierte la transacción
    $pdo->rollBack();
    header("Location: respuesta_cita.php?mensaje=ERROR%3A+No+se+pudo+reservar+la+cita");
    exit;
}
