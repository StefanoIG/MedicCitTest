<?php
session_start();
include 'php/config.php';

date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_ES.UTF-5');

// Función para obtener citas
function obtenerCitas($fecha, $pdo)
{
    $consulta = "SELECT COUNT(*) AS numero_citas FROM cita WHERE fecha_cita = :fecha";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute(['fecha' => $fecha]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['numero_citas'];
}

// Captura el mes y año actuales o los proporcionados a través de GET
$mes = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

// Crear timestamps para el mes anterior y el siguiente
$timestampActual = mktime(0, 0, 0, $mes, 1, $anio);
$mesAnterior = date('m', strtotime("-1 month", $timestampActual));
$anioAnterior = date('Y', strtotime("-1 month", $timestampActual));
$mesSiguiente = date('m', strtotime("+1 month", $timestampActual));
$anioSiguiente = date('Y', strtotime("+1 month", $timestampActual));

$diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
$primerDiaDelMes = mktime(0, 0, 0, $mes, 1, $anio);
$diaSemana = date('w', $primerDiaDelMes);
$nombreMes = strftime("%B", $timestampActual);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Tus etiquetas meta y title -->
    <link rel="stylesheet" href="css/dash.css">
    <title>Calendario - CitaMedica</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<div class="dashboard-wrapper">
    <nav class="sidebar">
        <h2>CitaMedica</h2>
        <ul class="nav-links">
            <li><a href="dashboard.php">Estadísticas</a></li>
            <li><a href="calendario.php">Calendario</a></li>
            <li><a href="pacientes.php">Pacientes</a></li>
            <li><a href="admin-trata.php">Lista de Tratamiento-Especialidad</a></li>

            <li><a href="logout.php">Cerrar Sesion</a></li>
        </ul>
    </nav>
    <main class="dashboard-content">
        <!-- Navegación de meses -->
        <div class="navegacion-meses">
            <a href="calendario.php?mes=<?php echo $mesAnterior; ?>&anio=<?php echo $anioAnterior; ?>">Mes anterior</a>
            <a href="calendario.php?mes=<?php echo $mesSiguiente; ?>&anio=<?php echo $anioSiguiente; ?>">Mes siguiente</a>
        </div>
        <h1>Calendario - <?php echo ucfirst($nombreMes) . ' ' . $anio; ?></h1>
        <table>
            <tr>
                <th>Domingo</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
            </tr>
            <tbody>
                <?php
                $contadorDias = 1;
                $numeroSemanas = ceil(($diaSemana + $diasEnMes) / 7);

                for ($i = 0; $i < $numeroSemanas; $i++) :
                    echo '<tr>';
                    for ($j = 0; $j < 7; $j++) :
                        echo '<td>';
                        if ($i === 0 && $j < $diaSemana) {
                            echo '&nbsp;';
                        } elseif ($contadorDias > $diasEnMes) {
                            break;
                        } else {
                            $fechaActual = $anio . '-' . $mes . '-' . str_pad($contadorDias, 2, '0', STR_PAD_LEFT);
                            $citasHoy = obtenerCitas($fechaActual, $pdo);
                            echo $contadorDias . ($citasHoy > 0 ? " (Citas: $citasHoy)" : '');
                            echo '<button class="mostrar-citas" data-fecha="' . $fechaActual . '">Citas (' . $citasHoy . ')</button>';

                            $contadorDias++;
                        }
                        echo '</td>';
                    endfor;
                    echo '</tr>';
                endfor;
                ?>
            </tbody>
        </table>
    </main>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar un evento clic para los botones con la clase "mostrar-citas"
        document.querySelectorAll('.mostrar-citas').forEach(function(boton) {
            boton.addEventListener('click', function() {
                // Obtener la fecha desde el atributo de datos
                var fecha = this.getAttribute('data-fecha');

                // Realizar una petición AJAX para obtener las citas del día
                fetch('obtener_citas.php?fecha=' + fecha)
                    .then(response => response.json())
                    .then(data => {
                        // Construir el contenido del modal con las citas
                        var modalContent = '<h2>Citas para el día ' + fecha + '</h2>';
                        if (data.length > 0) {
                            modalContent += '<ul>';
                            data.forEach(cita => {
                                modalContent += '<li>' +
                                    'Cita: ' + cita.id_cita +
                                    '<br>Hora: ' + cita.fecha_hora_cita +
                                    '<br>Usuario: ' + cita.nombres +
                                    '</li>';
                            });
                            modalContent += '</ul>';
                        } else {
                            modalContent += '<p>No hay citas para este día.</p>';
                        }

                        // Mostrar el modal con SweetAlert2
                        Swal.fire({
                            html: modalContent,
                            icon: 'info',
                        });
                    })
                    .catch(error => {
                        console.error('Error al obtener las citas:', error);
                        // Manejar el error, por ejemplo, mostrar un mensaje de error en el modal
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al obtener las citas. Por favor, inténtalo de nuevo.',
                            icon: 'error',
                        });
                    });
            });
        });
    });
</script>


</body>

</html>