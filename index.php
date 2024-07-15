<?php
session_start();
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultorio Medico Citas Medicas</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>

  <header class="main-header">
    <nav class="main-nav">
      <div class="nav-brand">
        <a href="index.php">Citas<strong>Medicas</strong></a>
      </div>
      <ul>
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#nosotros">Así somos</a></li>
        <li><a href="#servicios">Servicios</a></li>
        <li><a href="#footer">Contacto</a></li>

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


  <main>

    <section class="hero" style="background-image: url('img/fondo.jpg'); /* Asegúrate de que esta imagen está optimizada y es de alta calidad */">
      <h1>La Mejor Version De Tu Salud Comienza Aquí</h1>
      <a href="tratamientos.php" class="btn-tratamientos">Tratamientos-Especialidades</a>
    </section>

    <section id="nosotros" class="about-us">
      <div class="container">
        <h2>Acerca de Nosotros</h2>
        <div class="about-content">
          <div class="about-text">
            <p>Somos un equipo de medicos profesionales en diferentes especialidades, estamos comprometidos con la excelencia en el cuidado de la salud. Nuestra práctica está dedicada a proporcionar tratamientos de la más alta calidad en un ambiente cálido y acogedor.</p>
            <p>Entendemos la importancia de una estar saludable y trabajamos estrechamente con nuestros pacientes para crear planes de tratamiento personalizados que se ajusten a sus necesidades únicas.</p>
            <a href="register.php" class="btn">Unete ahora!</a>
          </div>
          <div class="about-image">
            <div class="slider" id="aboutSlider">
              <div class="slides">
                <img src="img/1.jpg" alt="Nuestro consultorio - Imagen 1">
                <img src="img/2.jpg" alt="Nuestro consultorio - Imagen 2">
                <img src="img/3.jpg" alt="Nuestro consultorio - Imagen 3">
                <!-- Más imágenes si es necesario -->
              </div>
              <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
              <a class="next" onclick="changeSlide(1)">&#10095;</a>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section id="footer">

      <footer class="site-footer">
        <div class="container">
          <div class="footer-content">
            <div class="footer-section">
              <h4>Contacto</h4>
              <p><strong>Dirección:</strong> 123 Avenida Principal, Ciudad, País</p>
              <p><strong>Teléfono:</strong> +1 234 567 8900</p>
              <p><strong>Email:</strong> contacto@dentalcare.com</p>
            </div>
            <div class="footer-section">
              <h4>Redes Sociales</h4>
              <p>Síguenos en nuestras redes sociales para mantenerte al día con las últimas noticias y promociones.</p>
              <ul class="social-list">
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Twitter</a></li>
              </ul>
            </div>
          </div>
          <div class="footer-bottom">
            <p>© 2023 DentalCare. Todos los derechos reservados.</p>
          </div>
        </div>
      </footer>
    </section>




    <script>
      var slideIndex = 0;
      showSlides(slideIndex);

      function changeSlide(n) {
        showSlides(slideIndex += n);
      }

      function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("slides")[0].getElementsByTagName("img");
        if (n >= slides.length) {
          slideIndex = 0
        }
        if (n < 0) {
          slideIndex = slides.length - 1
        }
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
        }
        slides[slideIndex].style.display = "block";
      }
    </script>
</body>

</html>