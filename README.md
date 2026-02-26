# proyecto_pesquera_version2
 La Pesquera – Sistema Web de Restaurante (PHP + MySQL)

La Pesquera es una plataforma web desarrollada para digitalizar los servicios de un restaurante. Permite a los usuarios registrarse, iniciar sesión, consultar productos, realizar pedidos, gestionar su carrito, hacer reservas y solicitar domicilios.

El objetivo principal es ofrecer una experiencia rápida, organizada y funcional tanto para clientes nuevos como recurrentes.

 Capturas de Pantalla

🔹 Inicio
<img width="1347" height="683" alt="image" src="https://github.com/user-attachments/assets/e97e2f8b-77e3-4d0c-a210-1cf5f835555d" />

🔹 Menú de Productos
<img width="1366" height="696" alt="image" src="https://github.com/user-attachments/assets/af0213c8-15cb-4bb8-bb9a-0b28e442042d" />

🔹 Carrito de Compras
<img width="1347" height="675" alt="image" src="https://github.com/user-attachments/assets/91ce2241-8c0b-4adb-b2be-930de442f370" />

🔹 Reservas
<img width="1352" height="630" alt="image" src="https://github.com/user-attachments/assets/0e667eea-a652-4d8f-a628-0369b0a833fc" />

🔹 Domicilios
<img width="1347" height="667" alt="image" src="https://github.com/user-attachments/assets/c129a429-4026-495e-83d7-d1b6ddecd46b" />

(Aquí puedes subir imágenes luego a GitHub y agregarlas)

 Instalación del Proyecto
1. Clonar el repositorio
git clone https://github.com/tu_usuario/la_pesquera.git
cd la_pesquera
2. Configurar entorno local

Este proyecto requiere un servidor local como:

XAMPP

WAMP

Laragon

Coloca el proyecto en:

C:\xampp\htdocs\
3. Base de datos

Abre phpMyAdmin

Crea una base de datos llamada:

la_pesquera

Importa el archivo .sql 

4. Configurar conexión

En el archivo:

php/modelo/conexion.php

Configura:

$conexion = new mysqli("localhost", "root", "", "la_pesquera");
 Uso del Sistema
 Usuario

Registro de cuenta

Inicio de sesión

Visualización de productos

Carrito de compras

Realización de pedidos

Reservas

Solicitud de domicilios

 Autenticación

Manejo de sesiones con $_SESSION

Protección de rutas con auth.php

 Tecnologías Utilizadas

PHP (Backend)

MySQL (Base de datos)

HTML5

CSS3

JavaScript

XAMPP (Servidor local)

Git y GitHub

 Estructura del Proyecto
css/
js/
paginas/
php/
   modelo/
   controlador/
   configuracion/
index.php
 Integrantes

Samuel Díaz García



 Estado del Proyecto

 En desarrollo / Funcional

 Mejoras Futuras

Subida de imágenes de productos

Validaciones con JavaScript

Mejoras en diseño responsive

Seguridad con consultas preparadas

 Licencia

Este proyecto es de uso académico y libre para aprendizaje.
