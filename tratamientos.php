<?php
session_start();
include 'php/config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$consultaTratamientos = "SELECT * FROM especialidad";
$stmt = $pdo->prepare($consultaTratamientos);
$stmt->execute();
$tratamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tratamientos - CitasMedicas</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <header class="main-header">
    <nav class="main-nav">
      <div class="nav-brand">
        <a href="index.php">Citasa<strong>Medicas</strong></a>
      </div>
      <ul>
        <li><a href="index.php#inicio">Inicio</a></li>
        <li><a href="index.php#nosotros">Así somos</a></li>
        <li><a href="index.php#servicios">Tratamientos</a></li>
        <li><a href="index.php#contacto">Contacto</a></li>

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
  </header>


  <main class="treatments-page">
    <aside class="filter-section">
      <h3>Filtrar Tratamientos</h3>

      <div class="filter-option">
        <label for="tipo-tratamiento">Tipo de Tratamiento:</label>
        <select id="tipo-tratamiento" name="tipo-tratamiento">
          <option value="ortodoncia">Ortodoncia</option>
          <option value="implantes">Implantes Dentales</option>
          <option value="limpieza">Limpieza Dental</option>
          <!-- Más opciones de tratamiento -->
        </select>
      </div>

      <div class="filter-option">
        <label for="rango-precio">Rango de Precios:</label>
        <input type="range" id="rango-precio" name="rango-precio" min="0" max="1000">
        <p id="precio-valor">Valor: $0 - $1000</p>
      </div>



      <!-- Botón para aplicar filtros -->
      <div class="filter-option">
        <button type="button" class="btn-filter">Aplicar Filtros</button>
      </div>
    </aside>



    <section class="treatments-container">
      <?php foreach ($tratamientos as $tratamiento) : ?>
        <div class="treatment-card" data-id="<?= $tratamiento['id_especialidad'] ?>">
          <img src="img/1.jpg" alt="<?= htmlspecialchars($tratamiento['nombre']) ?>">
          <h4><?= htmlspecialchars($tratamiento['nombre']) ?></h4>
          <p><?= htmlspecialchars($tratamiento['descripcion']) ?></p>
          <p><?= htmlspecialchars($tratamiento['precio']) ?></p>
        </div>
      <?php endforeach; ?>
    </section>



  </main>

  <script>
    window.onload = function() {
      var treatmentCards = document.querySelectorAll(".treatment-card");

      treatmentCards.forEach(function(card) {
        card.addEventListener("click", function() {
          var tratamientoId = this.getAttribute('data-id');
          window.location.href = "detail.php?id=" + tratamientoId;
        });
      });
    };
  </script>
</body>

</html>

</body>

</html>