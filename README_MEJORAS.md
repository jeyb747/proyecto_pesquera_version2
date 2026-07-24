# Mejoras incluidas

1. Importa `database_mejoras.sql` en la base de datos `la_pesquera` antes de usar las nuevas funciones.
2. En el panel de administrador encontrarás **Categorías**, para crear, editar y activar/desactivar categorías. El formulario de productos ahora usa dichas categorías.
3. El menú es público. Al intentar agregar un producto al carrito sin una sesión activa, el visitante es enviado al inicio de sesión.
4. El cliente puede abrir **Mis reservas y pedidos** desde la barra de navegación para editar o cancelar reservas pendientes y seguir el domicilio.
5. La recuperación de contraseña está disponible desde el inicio de sesión. Para Gmail: ejecuta `composer install`, copia `php/configuracion/mail_config.php.example` como `mail_config.php` y pega allí una contraseña de aplicación de Google.
6. Se envía correo al confirmar una reserva y cuando el domicilio cambia a **En camino**.

La carpeta debe publicarse como `version_final` dentro del directorio web, conservando las rutas existentes `/version_final/...`.
