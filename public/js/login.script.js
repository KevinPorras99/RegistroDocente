 // Funcionalidad para mostrar u ocultar la contraseña 
 const togglePassword = document.querySelector("#togglePassword");
 const password = document.querySelector("#password");
 const togglePasswordConfirmation = document.querySelector("#togglePasswordConfirmation");
 const passwordConfirmation = document.querySelector("#password_confirmation");
 const passwordRequirements = document.getElementById("passwordRequirements");
 const matchRequirements = document.getElementById("matchRequirements");

 const lengthRequirement = document.getElementById("lengthRequirement");
 const uppercaseRequirement = document.getElementById("uppercaseRequirement");
 const lowercaseRequirement = document.getElementById("lowercaseRequirement");
 const numberRequirement = document.getElementById("numberRequirement");
 const specialRequirement = document.getElementById("specialRequirement");
 const matchRequirement = document.getElementById("matchRequirement");

 password.addEventListener("focus", function () {
     passwordRequirements.style.display = "block";
 });

 password.addEventListener("blur", function () {
     passwordRequirements.style.display = "none";
 });

 passwordConfirmation.addEventListener("focus", function () {
     matchRequirements.style.display = "block";
 });

 passwordConfirmation.addEventListener("blur", function () {
     matchRequirements.style.display = "none";
 });

 togglePassword.addEventListener("click", function () {
     const type = password.getAttribute("type") === "password" ? "text" : "password";
     password.setAttribute("type", type);
     this.classList.toggle("fa-eye-slash");
 });

 togglePasswordConfirmation.addEventListener("click", function () {
     const type = passwordConfirmation.getAttribute("type") === "password" ? "text" : "password";
     passwordConfirmation.setAttribute("type", type);
     this.classList.toggle("fa-eye-slash");
 });

 togglePassword.addEventListener("mousedown", function (event) {
     event.preventDefault(); // Evita que el campo de contraseña pierda el foco
 });

 togglePasswordConfirmation.addEventListener("mousedown", function (event) {
     event.preventDefault(); // Evita que el campo de contraseña pierda el foco
 });

 submitBtn.addEventListener("mousedown", function (event) {
     event.preventDefault(); // Evita que el campo de contraseña pierda el foco
 });

 password.addEventListener("input", function () {
     const value = password.value;
     const lengthValid = value.length >= 8 && value.length <= 32;
     const uppercaseValid = /[A-Z]/.test(value);
     const lowercaseValid = /[a-z]/.test(value);
     const numberValid = /[0-9]/.test(value);
     const specialValid = /[@$!%*?&]/.test(value);

     updateRequirement(lengthRequirement, lengthValid);
     updateRequirement(uppercaseRequirement, uppercaseValid);
     updateRequirement(lowercaseRequirement, lowercaseValid);
     updateRequirement(numberRequirement, numberValid);
     updateRequirement(specialRequirement, specialValid);
 });

 passwordConfirmation.addEventListener("input", function () {
     const matchValid = password.value === passwordConfirmation.value;
     updateRequirement(matchRequirement, matchValid);
 });

 function updateRequirement(element, isValid) {
     const icon = element.querySelector('i');
     if (isValid) {
         element.classList.add("valid");
         element.classList.remove("invalid");
         icon.style.display = 'inline';
     } else {
         element.classList.remove("valid");
         element.classList.add("invalid");
         icon.style.display = 'none';
     }
 }

 document.addEventListener('DOMContentLoaded', function () {
     const elements = document.querySelectorAll('input, select, textarea');
     elements.forEach(function (element) {
         element.addEventListener('invalid', function (e) {
             // Restablecer el mensaje de error por defecto del navegador
             e.target.setCustomValidity('');

             if (!e.target.validity.valid) {
                 switch (e.target.id) {
                     case 'email':
                         e.target.setCustomValidity('Por favor, introduce una dirección de correo electrónico válida.');
                         break;
                     // Otros casos de validación personalizados aquí
                 }
             }
         });
     });
 });

 //Funcionalidad para mostrar u ocultar la contraseña
 document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('forgot-password-form');
    var submitBtn = document.getElementById('submitBtn');
    var successAlert = document.getElementById('success-alert');
    var errorAlert = document.getElementById('error-alert');

    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
    });

    // Función para ocultar el mensaje de éxito después de 3 segundos
    if (successAlert) {
        setTimeout(function () {
            successAlert.style.opacity = '0';
            setTimeout(function () {
                successAlert.remove();
            }, 500);
        }, 3000);
    }

    // Función para ocultar el mensaje de error después de 5 segundos
    if (errorAlert) {
        setTimeout(function () {
            errorAlert.style.opacity = '0';
            setTimeout(function () {
                errorAlert.remove();
            }, 500);
        }, 5000);
    }

    //Funcionalidad para mostrar u ocultar la contraseña
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function (e) {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        this.classList.toggle("fa-eye-slash");
    });

    // Código para hacer desaparecer el mensaje de éxito después de 3 segundos
    setTimeout(function() {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';
            setTimeout(function() {
                successMessage.remove();
            }, 500); // Esperar a que termine la transición antes de eliminar el elemento
        }
    }, 3000);


        // Funcionalidad para mostrar u ocultar la contraseña
        
        const togglePasswordConfirmation = document.querySelector("#togglePasswordConfirmation");
        const passwordConfirmation = document.querySelector("#password_confirmation");
        const passwordRequirements = document.getElementById("passwordRequirements");
        const matchRequirements = document.getElementById("matchRequirements");
    
        const lengthRequirement = document.getElementById("lengthRequirement");
        const uppercaseRequirement = document.getElementById("uppercaseRequirement");
        const lowercaseRequirement = document.getElementById("lowercaseRequirement");
        const numberRequirement = document.getElementById("numberRequirement");
        const specialRequirement = document.getElementById("specialRequirement");
        const matchRequirement = document.getElementById("matchRequirement");
    
        // Mostrar las restricciones cuando el usuario se enfoca en el campo de contraseña
        password.addEventListener("focus", function () {
            passwordRequirements.style.display = "block";
        });
    
        // Ocultar las restricciones cuando el usuario sale del campo de contraseña
        password.addEventListener("blur", function () {
            passwordRequirements.style.display = "none";
        });
    
        // Mostrar las restricciones cuando el usuario se enfoca en el campo de confirmar contraseña
        passwordConfirmation.addEventListener("focus", function () {
            matchRequirements.style.display = "block";
        });
    
        // Ocultar las restricciones cuando el usuario sale del campo de confirmar contraseña
        passwordConfirmation.addEventListener("blur", function () {
            matchRequirements.style.display = "none";
        });
        
        togglePassword.addEventListener("mousedown", function (event) {
            event.preventDefault(); // Evita que el campo de contraseña pierda el foco
        });
    
        togglePasswordConfirmation.addEventListener("mousedown", function (event) {
            event.preventDefault(); // Evita que el campo de contraseña pierda el foco
        });
    
        registerbutton.addEventListener("mousedown", function (event) {
            event.preventDefault(); // Evita que el campo de contraseña pierda el foco
        });
        
    
        // Validación en vivo de la contraseña
        password.addEventListener("input", function () {
            const value = password.value;
    
            // Longitud de la contraseña
            if (value.length >= 8 && value.length <= 32) {
                lengthRequirement.classList.add("valid");
                lengthRequirement.classList.remove("invalid");
            } else {
                lengthRequirement.classList.add("invalid");
                lengthRequirement.classList.remove("valid");
            }
    
            // Contiene una letra mayúscula
            if (/[A-Z]/.test(value)) {
                uppercaseRequirement.classList.add("valid");
                uppercaseRequirement.classList.remove("invalid");
            } else {
                uppercaseRequirement.classList.add("invalid");
                uppercaseRequirement.classList.remove("valid");
            }
    
            // Contiene una letra minúscula
            if (/[a-z]/.test(value)) {
                lowercaseRequirement.classList.add("valid");
                lowercaseRequirement.classList.remove("invalid");
            } else {
                lowercaseRequirement.classList.add("invalid");
                lowercaseRequirement.classList.remove("valid");
            }
    
            // Contiene un número
            if (/[0-9]/.test(value)) {
                numberRequirement.classList.add("valid");
                numberRequirement.classList.remove("invalid");
            } else {
                numberRequirement.classList.add("invalid");
                numberRequirement.classList.remove("valid");
            }
    
            // Contiene un carácter especial
            if (/[@$!%*?&]/.test(value)) {
                specialRequirement.classList.add("valid");
                specialRequirement.classList.remove("invalid");
            } else {
                specialRequirement.classList.add("invalid");
                specialRequirement.classList.remove("valid");
            }
    
            // Validar coincidencia de contraseñas
            if (password.value === passwordConfirmation.value && password.value !== "") {
                matchRequirement.classList.add("valid");
                matchRequirement.classList.remove("invalid");
            } else {
                matchRequirement.classList.add("invalid");
                matchRequirement.classList.remove("valid");
            }
        });
    
        // Validación en vivo de la coincidencia de contraseñas
        passwordConfirmation.addEventListener("input", function () {
            if (password.value === passwordConfirmation.value && password.value !== "") {
                matchRequirement.classList.add("valid");
                matchRequirement.classList.remove("invalid");
            } else {
                matchRequirement.classList.add("invalid");
                matchRequirement.classList.remove("valid");
            }
        });
    
        // Mostrar/Ocultar contraseña
        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    
        // Mostrar/Ocultar confirmar contraseña
        togglePasswordConfirmation.addEventListener("click", function () {
            const type = passwordConfirmation.getAttribute("type") === "password" ? "text" : "password";
            passwordConfirmation.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    
        document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('input, select, textarea');
    elements.forEach(function (element) {
        element.addEventListener('invalid', function (e) {
            // Restablecer el mensaje de error por defecto del navegador
            e.target.setCustomValidity('');
    
            if (!e.target.validity.valid) {
                switch (e.target.id) {
                    case 'email':
                        e.target.setCustomValidity('Por favor, introduce una dirección de correo electrónico válida.');
                        break;
                    case 'password':
                        if (e.target.validity.tooShort) {
                            e.target.setCustomValidity('El campo contraseña debe tener al menos 8 caracteres.');
                        } else if (e.target.validity.patternMismatch) {
                            e.target.setCustomValidity('La contraseña debe cumplir con los requisitos especificados.');
                        } else {
                            e.target.setCustomValidity('Por favor, introduce una contraseña válida.');
                        }
                        break;
                    case 'password_confirmation':
                        e.target.setCustomValidity('Las contraseñas deben coincidir.');
                        break;
                    case 'name':
                    case 'text':
                        e.target.setCustomValidity('Este campo es obligatorio.');
                        break;
                    default:
                        e.target.setCustomValidity('Este campo es obligatorio.');
                        break;
                }
            }
        });
    
        element.addEventListener('input', function (e) {
            // Restablecer el mensaje personalizado para permitir que el navegador muestre el mensaje predeterminado si es necesario
            e.target.setCustomValidity('');
        });
    });
    });
});

