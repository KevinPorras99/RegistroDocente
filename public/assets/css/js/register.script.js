document.addEventListener('DOMContentLoaded', function () {
    // Tu código de validación de contraseña aquí...
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

    togglePassword.addEventListener("mousedown", function (event) {
        event.preventDefault();
    });

    togglePasswordConfirmation.addEventListener("mousedown", function (event) {
        event.preventDefault();
    });

    registerbutton.addEventListener("mousedown", function (event) {
        event.preventDefault();
    });

    password.addEventListener("input", function () {
        const value = password.value;

        if (value.length >= 8 && value.length <= 32) {
            lengthRequirement.classList.add("valid");
            lengthRequirement.classList.remove("invalid");
        } else {
            lengthRequirement.classList.add("invalid");
            lengthRequirement.classList.remove("valid");
        }

        if (/[A-Z]/.test(value)) {
            uppercaseRequirement.classList.add("valid");
            uppercaseRequirement.classList.remove("invalid");
        } else {
            uppercaseRequirement.classList.add("invalid");
            uppercaseRequirement.classList.remove("valid");
        }

        if (/[a-z]/.test(value)) {
            lowercaseRequirement.classList.add("valid");
            lowercaseRequirement.classList.remove("invalid");
        } else {
            lowercaseRequirement.classList.add("invalid");
            lowercaseRequirement.classList.remove("valid");
        }

        if (/[0-9]/.test(value)) {
            numberRequirement.classList.add("valid");
            numberRequirement.classList.remove("invalid");
        } else {
            numberRequirement.classList.add("invalid");
            numberRequirement.classList.remove("valid");
        }

        if (/[@$!%*?&]/.test(value)) {
            specialRequirement.classList.add("valid");
            specialRequirement.classList.remove("invalid");
        } else {
            specialRequirement.classList.add("invalid");
            specialRequirement.classList.remove("valid");
        }

        if (password.value === passwordConfirmation.value && password.value !== "") {
            matchRequirement.classList.add("valid");
            matchRequirement.classList.remove("invalid");
        } else {
            matchRequirement.classList.add("invalid");
            matchRequirement.classList.remove("valid");
        }
    });

    passwordConfirmation.addEventListener("input", function () {
        if (password.value === passwordConfirmation.value && password.value !== "") {
            matchRequirement.classList.add("valid");
            matchRequirement.classList.remove("invalid");
        } else {
            matchRequirement.classList.add("invalid");
            matchRequirement.classList.remove("valid");
        }
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

    const elements = document.querySelectorAll('input, select, textarea');
    elements.forEach(function (element) {
        element.addEventListener('invalid', function (e) {
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
            e.target.setCustomValidity('');
        });
    });
});
