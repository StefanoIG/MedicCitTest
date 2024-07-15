<?php
// Inicia la sesión
session_start();

// Remueve todas las variables de sesión
$_SESSION = array();

// Destruye la sesión.
session_destroy();

// Redirige al usuario al login (o a la página que prefieras después del cierre de sesión)
header("location: index.php");
exit;
