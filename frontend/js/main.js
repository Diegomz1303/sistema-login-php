
document.addEventListener('DOMContentLoaded', function () {

   
    const loginContainer = document.getElementById('login-container');
    const registerContainer = document.getElementById('register-container');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const registerForm = document.getElementById('register-form');
    const responseMessage = document.getElementById('response-message');

    
    showRegisterLink.addEventListener('click', function (e) {
        e.preventDefault(); 
        loginContainer.classList.add('hidden');
        registerContainer.classList.remove('hidden');
    });

    showLoginLink.addEventListener('click', function (e) {
        e.preventDefault(); 
        registerContainer.classList.add('hidden');
        loginContainer.classList.remove('hidden');
    });



    registerForm.addEventListener('submit', function (e) {
        e.preventDefault(); 

        
        const formData = new FormData(registerForm);

        
        fetch('../backend/register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) 
        .then(data => {
            
            responseMessage.textContent = data.message;
            responseMessage.classList.remove('hidden', 'success', 'error');

            if (data.success) {
                responseMessage.classList.add('success');
                registerForm.reset();
                
                
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
            
            console.error('Error:', error);
            responseMessage.textContent = 'Ocurrió un error al conectar con el servidor.';
            responseMessage.classList.remove('hidden');
            responseMessage.classList.add('error');
        });
    });

});



    
    const loginForm = document.getElementById('login-form');

    
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(loginForm);

        fetch('../backend/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                
                window.location.href = 'tienda.php';
            } else {
               
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