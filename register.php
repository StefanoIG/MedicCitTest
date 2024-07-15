<?php
include 'php/config.php'; // Asegúrate de que este archivo contiene la información correcta para conectarte a la base de datos.


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombres = $_POST['nombre'];
  $apellidos = $_POST['apellido'];
  $email = $_POST['email'];
  $cedula = $_POST['cedula'];
  $direccion = $_POST['direccion'];
  $telefono = $_POST['telefono'];
  $respuesta_1 = $_POST['respuesta_1'];
  $respuesta_2 = $_POST['respuesta_2'];
  $respuesta_3 = $_POST['respuesta_3'];
  $password = $_POST['password']; // Considera encriptar la contraseña antes de guardarla en la base de datos.
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);
  // Aquí necesitarías validar y sanear los datos ingresados por el usuario antes de insertarlos en la base de datos.

  try {
    $sql = "INSERT INTO usuario (nombre, apellido, cedula, dirrecion, email, contrasena, movil, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombres, $apellidos, $cedula, $direccion, $email, $passwordHash, $telefono]);

    // Obtener el ID del usuario insertado
    $id_usuario = $pdo->lastInsertId();

    // Preparar la consulta para insertar las respuestas de seguridad en la tabla 'security_answers'
    $stmt = $pdo->prepare("INSERT INTO security_answers (id_usuario, respuesta_1, respuesta_2, respuesta_3) 
                                 VALUES (:id_usuario, :respuesta_1, :respuesta_2, :respuesta_3)");
    // Ejecutar la consulta
    $stmt->execute([
      'id_usuario' => $id_usuario,
      'respuesta_1' => $respuesta_1,
      'respuesta_2' => $respuesta_2,
      'respuesta_3' => $respuesta_3
    ]);

    // Generar el código único para el usuario
    $codigo_seguridad = generarCodigoUnico();

    // Actualizar el código único en la tabla de usuarios
    $stmt = $pdo->prepare("UPDATE usuarios SET codigo_seguridad = :codigo_seguridad WHERE id_usuario = :id_usuario");
    $stmt->execute([
      'codigo_seguridad' => $codigo_seguridad,
      'id_usuario' => $id_usuario
    ]);
  } catch (PDOException $e) {
    die("ERROR: No se pudo registrar el usuario. " . $e->getMessage());
  }
}

function generarCodigoUnico()
{
  // Lógica para generar el código único (puedes adaptarla según tus necesidades)
  return substr(md5(uniqid(mt_rand(), true)), 0, 6); // Ejemplo de generación de un código de 6 caracteres
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - DentalCare</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

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
        <li><a href="login.php">Iniciar Sesión</a></li>

      </ul>
    </nav>
  </header>

  <main>
    <div class="conta" style=" background-image: url('img/fondo.jpg');">
      <div class="register-container">
        <h2>Crear Cuenta</h2>
        <form class="register-form" action="register.php" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" id="nombre" name="nombre" required oninput="validarTexto(this);">
              <p id="nombreMensajeError" class="input-error-message"></p>
            </div>
            <div class="form-group">
              <label for="apellido">Apellido:</label>
              <input type="text" id="apellido" name="apellido" required oninput="validarTexto(this);">
              <p id="apellidoMensajeError" class="input-error-message"></p>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" id="registroEmailInput" name="email" required oninput="validarEmail()">
              <p id="resultadoEmail" class="input-error-message"></p>
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono:</label>
              <input type="tel" id="telefono" name="telefono" required oninput="validarTelefono()">
              <p id="resultadoTelefono" class="input-error-message"></p>
            </div>
          </div>
          <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="contrasena" name="password" required oninput="validarContrasena()">
            <p id="resultadoContrasena" class="input-error-message"></p>
          </div>
          <div class="form-group">
            <label for="password-confirm">Confirmar Contraseña:</label>
            <input type="password" id="confirmarContrasena" name="password-confirm" required oninput="verificarContrasenas()">
            <p id="mensajeContrasenas" class="input-error-message"></p>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="cedula">Cédula:</label>
              <input type="text" id="cedula" name="cedula" required oninput="validarCedula()">
              <p id="cedulaMensajeError" class="input-error-message"></p>
            </div>
            <div class="form-group">
              <label for="direccion">Dirección:</label>
              <input type="text" id="direccion" name="direccion" required>
              <p id="direccionMensajeError" class="input-error-message"></p>
            </div>



          </div>
          <div class="form-group form-group--security">
            <div class="form-field">
              <label for="pregunta1" class="form-label">Pregunta de Seguridad 1:</label>
              <span class="form-span">¿Cuál es el nombre de tu mascota?</span>
              <input type="text" id="pregunta1" name="respuesta_1" class="form-input" required </div>
              <div class="form-field">
                <label for="pregunta2" class="form-label">Pregunta de Seguridad 2:</label>
                <span class="form-span">¿Cuál es tu comida favorita?</span>
                <input type="text" id="pregunta2" name="respuesta_2" class="form-input" required </div>

                <div class="form-field">
                  <label for="pregunta3" class="form-label">Pregunta de Seguridad 3:</label>
                  <span class="form-span">¿Tu ciudad de nacimiento?</span>
                  <input type="text" id="pregunta3" name="respuesta_3" class="form-input" required </div>
                </div>


                <div class="form-group login-link">
                  <p>Ya tienes usuario, <a href="login.php" class="strong-link">Inicia Sesión</a></p>
                </div>


                <div class="form-group">
                  <button type="submit" class="btn" onclick="alertaRegistroExitoso()">Registrarse</button>
                </div>
        </form>
      </div>
    </div>
  </main>


  <script src="js/validaciones.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Función para validar que el texto no contenga números
    function validarTexto(input) {
      const errorMensaje = input.nextElementSibling; // Obtiene el elemento p que mostrará el mensaje de error

      // Expresión regular que coincide con cualquier carácter que no sea una letra o espacio
      const regex = /[^a-zA-Z\s]/g;

      // Comprueba si el valor del input contiene algún carácter no permitido
      if (regex.test(input.value)) {
        console.log('pendejo');
        errorMensaje.textContent = "Solo se permiten letras.";
        input.value = input.value.replace(regex, ''); // Elimina los caracteres no deseados
        return false
      } else {
        errorMensaje.textContent = ""; // Limpia el mensaje de error si todo está bien
        return true
      }
    }
  </script>

</body>

</html>