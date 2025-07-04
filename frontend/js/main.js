// frontend/js/main.js

// Espera a que todo el contenido del DOM esté cargado
document.addEventListener('DOMContentLoaded', function () {

    // --- SELECCIÓN DE ELEMENTOS ---
    const loginContainer = document.getElementById('login-container');
    const registerContainer = document.getElementById('register-container');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const registerForm = document.getElementById('register-form');
    const responseMessage = document.getElementById('response-message');

    // --- LÓGICA PARA CAMBIAR ENTRE FORMULARIOS ---
    showRegisterLink.addEventListener('click', function (e) {
        e.preventDefault(); // Evita que el enlace recargue la página
        loginContainer.classList.add('hidden');
        registerContainer.classList.remove('hidden');
    });

    showLoginLink.addEventListener('click', function (e) {
        e.preventDefault(); // Evita que el enlace recargue la página
        registerContainer.classList.add('hidden');
        loginContainer.classList.remove('hidden');
    });


    // --- LÓGICA PARA EL ENVÍO DEL FORMULARIO DE REGISTRO ---
    registerForm.addEventListener('submit', function (e) {
        e.preventDefault(); // ¡Muy importante! Evita el envío tradicional del formulario.

        // Crea un objeto FormData para recoger los datos del formulario
        const formData = new FormData(registerForm);

        // Usamos la API Fetch para enviar los datos al backend
        fetch('../backend/register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Esperamos una respuesta JSON
        .then(data => {
            // Mostramos el mensaje de respuesta
            responseMessage.textContent = data.message;
            responseMessage.classList.remove('hidden', 'success', 'error');

            if (data.success) {
                responseMessage.classList.add('success');
                registerForm.reset(); // Limpiamos el formulario
                
                // Opcional: Después de 2 segundos, oculta el mensaje y muestra el login
                setTimeout(() => {
                    responseMessage.classList.add('hidden');
                    registerContainer.classList.add('hidden');
                    loginContainer.classList.remove('hidden');
                }, 2000);

            } else {
                responseMessage.classList.add('error');
            }
        })
        .catch(error => {
            // Manejo de errores de red o del servidor
            console.error('Error:', error);
            responseMessage.textContent = 'Ocurrió un error al conectar con el servidor.';
            responseMessage.classList.remove('hidden');
            responseMessage.classList.add('error');
        });
    });

});

// frontend/js/main.js (Añadir este bloque de código)

    // --- SELECCIÓN DEL FORMULARIO DE LOGIN (añadir si no está) ---
    const loginForm = document.getElementById('login-form');

    // --- LÓGICA PARA EL ENVÍO DEL FORMULARIO DE LOGIN ---
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Evitamos el envío tradicional

        const formData = new FormData(loginForm);

        fetch('../backend/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si el login es exitoso, redirigimos al usuario a un "dashboard"
                // Crearemos esta página en el siguiente paso.
                window.location.href = 'dashboard.php';
            } else {
                // Si hay un error, lo mostramos
                responseMessage.textContent = data.message;
                responseMessage.classList.remove('hidden', 'success');
                responseMessage.classList.add('error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            responseMessage.textContent = 'Ocurrió un error al conectar con el servidor.';
            responseMessage.classList.remove('hidden');
            responseMessage.classList.add('error');
        });
    });