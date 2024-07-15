// Función para validar campos de entrada
function validarCampo(input) {
  const valor = input.value.trim();
  if (valor === '') {
      input.classList.add('invalid');
  } else {
      input.classList.remove('invalid');
  }
}

// Función para validar una dirección de correo electrónico
function validarEmail() {
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  const emailInput = document.getElementById("registroEmailInput");
  const resultadoEmail = document.getElementById("resultadoEmail");
  
  if (!emailRegex.test(emailInput.value)) {
      resultadoEmail.textContent = "La dirección de correo electrónico no es válida.";
      return false;
  }
  
  resultadoEmail.textContent = "";
  return true;
}

// Función para validar un número de teléfono
function validarTelefono() {
  const phoneRegex = /^\d{10}$/; // Solo acepta exactamente 10 dígitos
  const telefonoInput = document.getElementById("telefono");
  const resultadoTelefono = document.getElementById("resultadoTelefono");
  
  if (!phoneRegex.test(telefonoInput.value)) {
      resultadoTelefono.textContent = "El número de teléfono no es válido. Debe contener exactamente 10 dígitos y ningún otro carácter.";
      return false;
  }
  resultadoTelefono.textContent = "";
  
  return true;
}


// Función para validar contraseñas
function validarContrasena() {
  const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{10,}$/;
  const contrasenaInput = document.getElementById("contrasena");
  const resultadoContrasena = document.getElementById("resultadoContrasena");

  if (!passwordRegex.test(contrasenaInput.value)) {
      resultadoContrasena.textContent = "La contraseña no es válida. Debe contener al menos un carácter en mayúscula, al menos un dígito y tener al menos 10 caracteres.";
      return false;
  }
  resultadoContrasena.textContent = "";
  return true;
}

// Función para verificar que las contraseñas coincidan
function verificarContrasenas() {
  const contrasenaInput = document.getElementById("contrasena").value;
  const confirmarContrasenaInput = document.getElementById("confirmarContrasena").value;
  const mensajeContrasenas = document.getElementById("mensajeContrasenas");

  if (contrasenaInput !== confirmarContrasenaInput) {
      mensajeContrasenas.textContent = "Las contraseñas no coinciden.";
      return false;
  } else {
      mensajeContrasenas.textContent = "";
      return true;
  }
}



// Alerta para registro exitoso que redirige a login
function alertaRegistroExitoso() {
  Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'Registro con éxito',
    showConfirmButton: false,
    timer: 1500
  }).then(() => {
    window.location.href = 'login.php';
  });
}


// Validación de una cédula personalizada
function validarCedula() {
  const cedulaPersonalizada = document.getElementById("cedula").value;
  const mensajeCedulaPersonalizada = document.getElementById("cedulaMensajeError");
  
  if (cedulaPersonalizada.length !== 10) {
    mensajeCedulaPersonalizada.textContent = "La cédula debe tener 10 dígitos.";
    return false;
  }

  if (!/^[0-9]+$/.test(cedulaPersonalizada)) {
    mensajeCedulaPersonalizada.textContent = "La cédula debe contener solo números.";
    return false;
  }

  const provincia = Number(cedulaPersonalizada.substring(0, 2));
  if (provincia < 1 || provincia > 24) {
    mensajeCedulaPersonalizada.textContent = "El primer número de la cédula debe estar entre 1 y 24.";
    return false;
  }

  const coeficientesCedula = [2, 1, 2, 1, 2, 1, 2, 1, 2];
  const digitoVerificadorCedula = Number(cedulaPersonalizada[9]);

  let sumaCedula = 0;
  for (let i = 0; i < 9; i++) {
    let valorCedula = Number(cedulaPersonalizada[i]) * coeficientesCedula[i];
    if (valorCedula > 9) {
      valorCedula -= 9;
    }
    sumaCedula += valorCedula;
  }

  const totalCedula = (Math.ceil(sumaCedula / 10) * 10);
  const digitoVerificadorCalculadoCedula = totalCedula - sumaCedula;

  if (digitoVerificadorCalculadoCedula === 10) {
    if (digitoVerificadorCedula !== 0) {
      mensajeCedulaPersonalizada.textContent = "La cédula es inválida.";
      return false;
    }
  } else {
    if (digitoVerificadorCedula !== digitoVerificadorCalculadoCedula) {
      mensajeCedulaPersonalizada.textContent = "La cédula es inválida.";
      return false;
    }
  }

  mensajeCedulaPersonalizada.textContent = "";
  return true;
}