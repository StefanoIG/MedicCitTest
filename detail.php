<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
$tratamientoId = isset($_GET['id']) ? $_GET['id'] : null;

if ($tratamientoId) {
  include 'php/config.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

  $consulta = "SELECT * FROM especialidad WHERE id_especialidad = ?";
  $stmt = $pdo->prepare($consulta);
  $stmt->execute([$tratamientoId]);
  $detalleTratamiento = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$detalleTratamiento) {
    // Manejar el caso en que no se encuentre el tratamiento
    exit('Tratamiento no encontrado.');
  }
} else {
  // Redirigir o manejar el caso de no tener un ID
  exit('No se proporcionó un ID de tratamiento válido.');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del Tratamiento - DentalCare</title>
  <link rel="stylesheet" href="css/styles.css">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

  <nav class="main-nav">
    <div class="nav-brand">
      <a href="index.html">Dental<strong>Care</strong></a>
    </div>
    <ul>
      <li><a href="tratamientos.html">Tratamientos</a></li>
      <li><a href="index.html#inicio">Cerrar Sesion</a></li>
      <li><a href="index.html#nosotros">Así somos</a></li>
      <li><a href="#footer">Contacto</a></li>
    </ul>
  </nav>
  </header>

  <main class="treatment-detail-page">
    <div class="treatment-content">
      <div class="treatment-slider">
        <!-- Slider con imágenes del tratamiento -->
      </div>

      <section class="treatment-info">
        <h2><?= htmlspecialchars($detalleTratamiento['nombre']) ?></h2>
        <p><?= htmlspecialchars($detalleTratamiento['descripcion']) ?></p>
        <p><strong>Precio:</strong> <?= htmlspecialchars($detalleTratamiento['precio']) ?></p>
        <p><strong>Descripcion:</strong> <?= htmlspecialchars($detalleTratamiento['descripcion']) ?></p>
        <button class="btn" onclick="separarTurno(<?= $detalleTratamiento['id_especialidad'] ?>)">Separar Turno</button>
      </section>
    </div>
  </main>

  <form id="form-reserva-cita" action="reservar_cita.php" method="post" style="display:none;">
    <input type="hidden" name="id_tratamiento" id="inputIdTratamiento">
    <input type="hidden" name="fecha_cita" id="inputFechaCita">
    <input type="hidden" name="hora_cita" id="inputHoraCita">

  </form>


  <script>
    // Función para manejar la separación de turno
    function separarTurno(id_tratamiento) {
      const fechaHoy = new Date().toISOString().split('T')[0];

      Swal.fire({
        title: 'Selecciona la fecha de tu cita',
        html: `<input type="date" id="fecha-cita" class="swal2-input" min="${fechaHoy}">`,
        showCancelButton: true,
        confirmButtonText: 'Siguiente',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
          const fecha = document.getElementById('fecha-cita').value;
          return fecha ? fecha : Swal.showValidationMessage('Por favor seleccione una fecha');
        }
      }).then((result) => {
        if (result.isConfirmed) {
          solicitarHora(result.value, id_tratamiento);
        }
      });
    }

    function solicitarHora(fecha, id_tratamiento) {
      Swal.fire({
        title: 'Selecciona la hora de tu cita',
        html: '<input type="time" id="hora-cita" class="swal2-input">',
        showCancelButton: true,
        confirmButtonText: 'Reservar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
          const horaCita = document.getElementById('hora-cita').value;
          const [hora, minutos] = horaCita.split(':').map(Number);

          // Verifica si la hora está dentro del rango permitido
          if (horaCita && hora >= 9 && (hora < 18 || (hora === 18 && minutos === 0))) {
            return horaCita;
          } else {
            Swal.showValidationMessage('Por favor seleccione una hora entre las 9:00 y las 18:00.');
          }
        }
      }).then((resultHora) => {
        if (resultHora.isConfirmed) {
          Swal.fire(`Cita reservada para el ${fecha} a las ${resultHora.value}`);
          document.getElementById('inputIdTratamiento').value = id_tratamiento;
          document.getElementById('inputFechaCita').value = fecha; // Cambio aquí
          document.getElementById('inputHoraCita').value = resultHora.value;
          // Envía el formulario
          document.getElementById('form-reserva-cita').submit();
        }
      });
    }
  </script>
</body>

</html>

</body>

</html>