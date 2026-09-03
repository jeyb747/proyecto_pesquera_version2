La Pesquera – Sistema Web de Restaurante (PHP + MySQL)

La Pesquera es una plataforma web para digitalizar la operación de un restaurante: catálogo de productos, carrito de compras, pedidos, reservas y domicilios, con un panel de administración completo y un panel para repartidores.

El proyecto nació como una versión sencilla para XAMPP (carpeta version_final/) y evolucionó hacia una aplicación con backend orientado a API, autenticación por roles, envío de correos y despliegue en la nube (Azure App Service vía GitHub Actions, con una ruta alternativa a Railway/Docker documentada como legado).

 Capturas de Pantalla

🔹 Inicio <img width="1347" height="683" alt="Inicio" src="https://github.com/user-attachments/assets/e97e2f8b-77e3-4d0c-a210-1cf5f835555d" />

🔹 Menú de Productos <img width="1366" height="696" alt="Menú" src="https://github.com/user-attachments/assets/af0213c8-15cb-4bb8-bb9a-0b28e442042d" />

🔹 Carrito de Compras <img width="1347" height="675" alt="Carrito" src="https://github.com/user-attachments/assets/91ce2241-8c0b-4adb-b2be-930de442f370" />

🔹 Reservas <img width="1352" height="630" alt="Reservas" src="https://github.com/user-attachments/assets/0e667eea-a652-4d8f-a628-0369b0a833fc" />

🔹 Domicilios <img width="1347" height="667" alt="Domicilios" src="https://github.com/user-attachments/assets/c129a429-4026-495e-83d7-d1b6ddecd46b" />

 Funcionalidades
Cliente
Registro de cuenta con verificación de correo electrónico.
Inicio de sesión y recuperación de contraseña por correo (token de restablecimiento).
Catálogo de productos público, organizado por categorías.
Carrito de compras y realización de pedidos.
Reservas de mesa, con panel "Mis reservas y pedidos" para editar o cancelar reservas pendientes y hacer seguimiento del domicilio.
Solicitud de domicilios.
Correos automáticos al confirmar una reserva y cuando un domicilio cambia a "En camino".
Panel de administración (/paginas/admin)
Dashboard general.
Gestión de productos y categorías (crear, editar, activar/desactivar).
Gestión de pedidos, reservas, domicilios y usuarios.
Gestión de roles y tipos de documento.
Configuración del sistema (productos, pedidos, reservas, domicilios, usuarios, roles).
Acceso restringido por rol (admin_auth.php).
Panel de repartidor (/paginas/repartidor)
Listado y detalle de domicilios asignados.
Cambio de estado del domicilio.
Asistente de rutas ("Capitán Ruta"): widget que sugiere agrupar pedidos cercanos en una misma ruta de entrega.
Asignación equitativa de domicilios entre repartidores.
API REST (/api)

Endpoints JSON independientes, pensados para consumo externo o pruebas:

productos.php — catálogo de productos.
registro.php — registro de usuarios.
login.php — autenticación.
reservas.php — reservas.
domicilios.php — domicilios.
password_recovery.php — recuperación de contraseña.
Autenticación y seguridad
Manejo de sesiones con $_SESSION.
Protección de rutas de cliente con php/configuracion/auth.php.
Protección de rutas de administrador con php/configuracion/admin_auth.php (verifica $_SESSION['rol'] == 'admin').
Verificación de correo (php/configuracion/verificacion.php).
Conexión a MySQL por variables de entorno, con mysqli_report en modo estricto y conexión SSL (MYSQLI_CLIENT_SSL).
🛠️ Tecnologías Utilizadas
PHP 8.x (backend, sin framework)
MySQL / MySQLi (con conexión SSL)
HTML5, CSS3, JavaScript (vanilla)
Bootstrap 5 (vía CDN)
PHPMailer (envío de correos vía SMTP de Gmail), gestionado con Composer
GitHub Actions → despliegue continuo a Azure App Service
Alternativa documentada: Docker + Railway (carpeta legacy-railway-docker/)
 Estructura del Proyecto
├── api/                     # Endpoints JSON (productos, login, registro, reservas, domicilios...)
├── css/                     # Estilos, incluye subcarpeta admin/ e includes/
├── js/                      # Scripts (carrito, reservas, historial, asistente de rutas...)
├── imagenes/                # Imágenes de productos y assets del sitio
├── includes/                # Componentes reutilizables (navbar, sidebar, topbar, flash, asistente de rutas)
├── paginas/                 # Vistas del cliente (menú, carrito, reservas, domicilio, login, registro...)
│   ├── admin/                # Panel de administración (productos, pedidos, reservas, usuarios, config...)
│   └── repartidor/           # Panel de repartidor
├── php/
│   ├── configuracion/        # auth.php, admin_auth.php, conexion.php, mail.php, verificacion.php
│   ├── controlador/          # Lógica de negocio por módulo (productos, pedidos, reservas, domicilios...)
│   └── modelo/                # Acceso a datos (conexion.php, login.php, loghout.php)
├── legacy-railway-docker/   # Dockerfile y guía alternativa de despliegue en Railway (legado)
├── version_final/           # Versión original simplificada para XAMPP (histórica)
├── composer.json             # Dependencia: phpmailer/phpmailer
└── index.php                 # Página de inicio

 Instalación y Configuración
Opción A — Entorno local con XAMPP / WAMP / Laragon
Clonar el repositorio
bash
   git clone https://github.com/tu_usuario/la_pesquera.git
   cd la_pesquera
Ubicar el proyecto en el servidor local
   C:\xampp\htdocs\la_pesquera
Instalar dependencias PHP (necesario para el envío de correos)
bash
   composer install
Crear la base de datos
Abre phpMyAdmin y crea una base de datos llamada la_pesquera.
Importa el archivo .sql correspondiente al esquema del proyecto.
Configurar la conexión a la base de datos php/modelo/conexion.php lee la configuración desde variables de entorno (no desde valores fijos en el código):
Variable	Descripción
DB_HOST	Host de la base de datos
DB_USER	Usuario de MySQL
DB_PASSWORD	Contraseña de MySQL
DB_NAME	Nombre de la base de datos (la_pesquera)
DB_PORT	Puerto (por defecto 3306)
Puedes definirlas en tu sistema, en la configuración de Apache/PHP, o con un plugin de variables de entorno para XAMPP. Ten en cuenta que la conexión se realiza forzando SSL (MYSQLI_CLIENT_SSL); en un entorno local sin SSL configurado en MySQL, es posible que debas ajustar temporalmente conexion.php para desarrollo.
Configurar el envío de correos (opcional, para verificación y recuperación de contraseña)
Copia php/configuracion/mail_config.php.example como php/configuracion/mail_config.php.
Completa username y password con una contraseña de aplicación de Google (16 caracteres), no la contraseña normal de Gmail.
Alternativamente, define las variables de entorno MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_NAME.
Acceder a la aplicación
   http://localhost/la_pesquera/
Opción B — Despliegue en Azure App Service (producción)

El repositorio incluye el workflow .github/workflows/master_la-pesquera-v2.yml, que en cada push a master:

Instala PHP 8.4 y ejecuta composer install.
Empaqueta index.php, api/, css/, imagenes/, includes/, js/, paginas/, php/ y vendor/.
Despliega automáticamente al App Service la-pesquera-v2 usando OpenID Connect (secretos AZUREAPPSERVICE_CLIENTID_*, TENANTID_*, SUBSCRIPTIONID_*).

Configura en Azure App Service las variables de entorno DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_NAME y APP_BASE_URL (ver php/configuracion/app_config.php.example).

Opción C — Despliegue en Railway (legado)

Ver legacy-railway-docker/README_RAILWAY.md. Usa el Dockerfile incluido en esa carpeta (PHP 8.2 + Apache), un servicio MySQL de Railway y el archivo database_railway.sql para crear la base de datos. Las variables de entorno de ejemplo están en legacy-railway-docker/.env.railway.example.

 Roles de Usuario
Rol	Acceso
Cliente	Menú, carrito, pedidos, reservas, domicilios, historial personal
Administrador	Panel /paginas/admin: productos, categorías, pedidos, reservas, domicilios, usuarios, roles y configuración general
Repartidor	Panel /paginas/repartidor: domicilios asignados, asistente de rutas, cambio de estado
 Notas de Seguridad
Nunca subas php/configuracion/mail_config.php ni archivos .env al repositorio (ya están cubiertos por .gitignore).
Las contraseñas de usuarios y credenciales de correo deben manejarse siempre mediante variables de entorno en producción.
La conexión a la base de datos exige SSL y falla de forma controlada (HTTP 500) si no puede establecerse.
 Mejoras Futuras
Subida de imágenes de productos desde el panel de administración.
Más validaciones en el cliente con JavaScript.
Mejoras adicionales en el diseño responsive.
Pruebas automatizadas para los controladores PHP.
Documentar/versionar el script .sql del esquema completo dentro del repositorio.
 Integrantes
Samuel Díaz García
 Estado del Proyecto

En desarrollo / Funcional — con despliegue activo en Azure App Service.

Licencia

Este proyecto es de uso académico y libre para aprendizaje.

 Licencia

Este proyecto es de uso académico y libre para aprendizaje.
