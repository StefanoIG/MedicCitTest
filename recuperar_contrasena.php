<?php
session_start();
include 'php/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];

    // Verificar si el correo existe en la base de datos
    $consulta = "SELECT * FROM usuario WHERE correo = :correo";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Generar una nueva contraseña temporal
        $nuevaContrasena = bin2hex(random_bytes(8)); // Genera una cadena aleatoria de 16 caracteres

        // Actualizar la contraseña en la base de datos
        $consultaUpdate = "UPDATE usuario SET contrasena = :contrasena WHERE correo = :correo";
        $stmtUpdate = $pdo->prepare($consultaUpdate);
        $stmtUpdate->execute(['contrasena' => password_hash($nuevaContrasena, PASSWORD_DEFAULT), 'correo' => $correo]);

        // Enviar un correo electrónico con la nueva contraseña (esto es un ejemplo, ajusta según tu entorno)
        $asunto = 'Recuperación de contraseña';
        $mensaje = 'Tu nueva contraseña es: ' . $nuevaContrasena;
        mail($correo, $asunto, $mensaje);

        // Mostrar un mensaje de éxito
        echo json_encode(['success' => true, 'message' => 'Se ha enviado una nueva contraseña a tu correo electrónico.']);
    } else {
        // Mostrar un mensaje de error
        echo json_encode(['success' => false, 'message' => 'El correo electrónico no está registrado en nuestro sistema.']);
    }
} else {
    // Si no es una solicitud POST, redirigir o manejar según tus necesidades
    header('Location: index.php');
    exit();
}
