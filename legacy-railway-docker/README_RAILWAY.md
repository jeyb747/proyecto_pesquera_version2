# Despliegue en Railway

1. Sube esta carpeta a un repositorio de GitHub. No subas `mail_config.php` ni contraseñas.
2. En Railway crea un proyecto vacío, agrega **MySQL** y luego agrega el repositorio como un segundo servicio.
3. En el servicio web, abre **Variables** y copia los valores de `.env.railway.example`. Cambia `MySQL` por el nombre real de tu servicio de base de datos si es distinto.
4. En el servicio MySQL usa **Connect** para obtener host y credenciales públicas, e importa `database_railway.sql` con MySQL Workbench o consola. El archivo ya crea la base `la_pesquera`.
5. Despliega. Railway detectará el `Dockerfile`. En Networking genera un dominio público y abre `https://TU-DOMINIO/version_final/`.

Las credenciales de Gmail deben ponerse únicamente en Variables de Railway: `MAIL_USERNAME` y `MAIL_PASSWORD`.
