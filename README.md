Sistema de Login y Registro (PHP y Google OAuth)
Este es un sistema de autenticación de usuarios para un sitio web, implementado con PHP, MySQL y la API de Google para el inicio de sesión social. El proyecto está estructurado con una separación clara entre el backend (lógica del servidor) y el frontend (interfaz de usuario).

Características
✅ Registro de usuarios con email y contraseña.

✅ Inicio de sesión con credenciales tradicionales.

🔒 Cifrado seguro de contraseñas en la base de datos (password_hash).

🚀 Registro e inicio de sesión con un clic usando una cuenta de Google (OAuth 2.0).

🛡️ Página de "dashboard" protegida, accesible solo para usuarios autenticados.

🚪 Funcionalidad de cierre de sesión seguro.

Prerrequisitos
Asegúrate de tener instalado el siguiente software en tu computadora:

XAMPP: para el servidor web Apache, PHP y la base de datos MySQL. Puedes descargarlo aquí.

Composer: para gestionar las dependencias de PHP. Puedes descargarlo aquí.

Una cuenta de Google para configurar las credenciales de la API.

Instalación y Configuración
Sigue estos pasos en orden para poner en marcha el proyecto:

1. Configuración del Proyecto
Clona o descarga este repositorio.

Coloca la carpeta del proyecto (mi_proyecto) dentro del directorio htdocs de tu instalación de XAMPP (normalmente C:\xampp\htdocs\).

Abre el panel de control de XAMPP e inicia los servicios de Apache y MySQL.

2. Base de Datos
Abre tu navegador y ve a http://localhost/phpmyadmin/.

Crea una nueva base de datos llamada login_db con el cotejamiento utf8mb4_general_ci.

Selecciona la base de datos login_db, ve a la pestaña "SQL" y ejecuta el siguiente script para crear la tabla usuarios:

SQL

CREATE TABLE `usuarios` ((
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
Verifica que las credenciales de la base de datos en backend/db_connect.php coincidan con tu configuración de XAMPP (por defecto son localhost, root y contraseña vacía).

3. Dependencias de PHP
Abre una terminal o línea de comandos.

Navega hasta la carpeta raíz del proyecto:

Bash

cd C:\xampp\htdocs\mi_proyecto
Instala las dependencias necesarias con Composer:

Bash

composer install
Esto creará una carpeta vendor con la librería de Google API.

4. Credenciales de Google (OAuth 2.0)
Este es el paso más importante para que el login con Google funcione.

Ve a la Consola de Google Cloud y crea un nuevo proyecto.

En el menú, ve a APIs y servicios > Biblioteca, busca y habilita la "Google People API".

Ve a APIs y servicios > Pantalla de consentimiento de OAuth.

Selecciona tipo "Externo".

Rellena los campos obligatorios (nombre de la app, tu email).

En la sección "Usuarios de prueba", añade tu propia dirección de correo de Google. Mientras la app esté en modo de prueba, solo los usuarios en esta lista podrán iniciar sesión.

Ve a APIs y servicios > Credenciales.

Haz clic en "+ CREAR CREDENCIALES" y selecciona "ID de cliente de OAuth".

Tipo de aplicación: "Aplicación web".

URIs de redireccionamiento autorizados: Añade la siguiente URL:

http://localhost/mi_proyecto/backend/google-callback.php
Haz clic en "CREAR".

Copia tu ID de cliente y tu Secreto del cliente.

Abre los archivos backend/google-login.php y backend/google-callback.php y pega tus credenciales donde se indica:

PHP

//// Ejemplo en ambos archivos
$client->setClientId('TU_ID_DE_CLIENTE_AQUÍ');
$client->setClientSecret('TU_SECRETO_DE_CLIENTE_AQUÍ');
