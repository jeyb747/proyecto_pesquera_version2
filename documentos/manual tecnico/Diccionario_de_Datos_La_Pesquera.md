# Diccionario de datos de La Pesquera

**Proyecto:** Sistema web del restaurante La Pesquera  
**Motor de base de datos:** MySQL 8 / MariaDB compatible  
**Codificación de conexión:** `utf8mb4`  
**Fecha:** 22 de octubre de 2026

## Alcance y lectura

Este diccionario describe las entidades y campos usados por las páginas, controladores y API actuales del proyecto. Los tipos indicados son **referenciales**: se infieren de los valores que el código lee y escribe. El repositorio no contiene el DDL de la base de datos, por lo que longitudes, nulabilidad, valores por defecto, índices y restricciones físicas deben verificarse en la instancia instalada con `SHOW CREATE TABLE nombre_tabla`.

El panel administrativo permite agregar o retirar algunas columnas dinámicamente. Esas columnas personalizadas no se incluyen en el núcleo de este documento. Las claves marcadas como **lógicas** se deducen de los `JOIN` y del uso de identificadores; su declaración física como `FOREIGN KEY` no está confirmada.

## Resumen de entidades

| Tabla | Propósito |
|---|---|
| `usuarios` | Cuentas de clientes, administradores y repartidores. |
| `roles` | Roles asignables a las cuentas. |
| `tipo_documento` | Catálogo de tipos de identificación. |
| `categorias` | Clasificación administrativa de productos. |
| `productos` | Catálogo, precio, inventario y estado de productos. |
| `pedidos` | Compra de un usuario y detalle de productos serializado. |
| `domicilios` | Dirección, seguimiento y asignación de entregas. |
| `reservas` | Reservas de mesa asociadas a un usuario. |
| `password_resets` | Solicitudes temporales para restablecer contraseñas. |

## Relaciones principales

| Origen | Destino | Relación observada |
|---|---|---|
| `roles.id` | `usuarios.id_rol` | Un rol puede asignarse a varios usuarios. |
| `tipo_documento.id` | `usuarios.id_tipo_documento` | Un tipo de documento puede corresponder a varios usuarios. |
| `usuarios.id` | `pedidos.usuario_id` | Un usuario puede registrar varios pedidos. Algunas rutas antiguas admiten `id_usuario`. |
| `usuarios.id` | `reservas.usuario_id` | Un usuario puede crear varias reservas. |
| `pedidos.id` | `domicilios.pedido_id` | Un pedido puede generar un domicilio. Algunas rutas antiguas admiten `id_pedido`. |
| `usuarios.id` | `password_resets.usuario_id` | Un usuario puede generar varias solicitudes de recuperación. |
| `categorias.nombre` | `productos.categoria` | La aplicación actual usa el nombre de categoría como texto; no se confirma una clave foránea. |
| `usuarios.nombre` | `domicilios.repartidor` | La asignación actual guarda el nombre del repartidor como texto; no se confirma una clave foránea. |

## Tabla `usuarios`

Identidad, autenticación y rol de acceso.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador interno de la cuenta. |
| `nombre` | `VARCHAR` | — | Nombre que se muestra en el sistema. |
| `id_tipo_documento` | `INT` | FK lógica | Referencia a `tipo_documento.id`. |
| `numero_documento` | `VARCHAR` | — | Número de identificación; texto para conservar ceros iniciales y formatos. |
| `correo` | `VARCHAR` | Regla de aplicación | Correo de inicio de sesión y contacto. El registro comprueba duplicados. |
| `telefono` | `VARCHAR` | — | Teléfono de contacto. |
| `password` | `VARCHAR(255)` | Hash | Contraseña procesada con `password_hash`; no debe almacenarse en texto plano. |
| `id_rol` | `INT` | FK lógica | Referencia a `roles.id`. |
| `estado` | `TINYINT` / `INT` | — | Estado de la cuenta. La asignación de domicilios considera activo el valor `1` y también admite `NULL`. |
| `correo_verificado` | `TINYINT(1)` | Puede ser `NULL` | Indicador de verificación: `0` pendiente y `1` verificado. |
| `codigo_verificacion_hash` | `VARCHAR(255)` | Puede ser `NULL` | Hash del código temporal de verificación. |
| `codigo_verificacion_expira` | `DATETIME` | Puede ser `NULL` | Fecha y hora de vencimiento del código. |

## Tabla `roles`

Catálogo de permisos funcionales.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador del rol. |
| `nombre_rol` | `VARCHAR` | — | Nombre del rol. La aplicación contempla `admin`, `repartidor` y `cliente`. |
| `descripcion` | `VARCHAR` / `TEXT` | Opcional según configuración | Descripción legible del rol. |

## Tabla `tipo_documento`

Catálogo de identificaciones solicitadas al registrar usuarios.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador del tipo de documento. |
| `nombre` | `VARCHAR` | — | Nombre del tipo de documento, por ejemplo, cédula. |

## Tabla `categorias`

Clasificación y habilitación de categorías del catálogo.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador de la categoría. |
| `nombre` | `VARCHAR` | — | Nombre visible de la categoría. |
| `descripcion` | `TEXT` / `VARCHAR` | Puede ser opcional | Descripción de la categoría. |
| `estado` | `TINYINT(1)` / `INT` | — | Indicador activo/inactivo; la aplicación usa `1` para activa y `0` para inactiva. |

## Tabla `productos`

Productos ofrecidos por el restaurante y su disponibilidad.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador del producto. |
| `nombre` | `VARCHAR` | — | Nombre del producto. |
| `descripcion` | `TEXT` / `VARCHAR` | — | Descripción del producto. |
| `precio` | `DECIMAL` | — | Precio de venta; la precisión y escala dependen de la tabla instalada. |
| `categoria` | `VARCHAR` | Texto en el código actual | Categoría asociada al producto. Los controladores actuales reciben y guardan el nombre como texto. |
| `stock` | `INT` | — | Unidades disponibles; el panel administrativo lo trata como entero. |
| `estado` | `TINYINT(1)` / `INT` | — | Indicador de producto activo/inactivo. |

## Tabla `pedidos`

Compra realizada por un usuario y su estado de procesamiento.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador del pedido. |
| `usuario_id` | `INT` | FK lógica | Usuario que realizó el pedido. Algunas rutas antiguas admiten `id_usuario`. |
| `productos` | `TEXT` / JSON | JSON serializado | Detalle de productos; el código lee elementos con nombre, categoría, cantidad y precio. |
| `total` | `DECIMAL` | — | Valor monetario total del pedido. |
| `estado` | `VARCHAR` | Opcional en algunos flujos | Estado del pedido; el flujo de compra inicia el pedido como pendiente cuando la columna existe. |
| `fecha` | `DATETIME` | — | Fecha y hora de creación del pedido. |
| `pago` | `VARCHAR` | Opcional según esquema | Método de pago seleccionado. |
| `observaciones` | `TEXT` / `VARCHAR` | Opcional según esquema | Notas del cliente asociadas al pedido. |

## Tabla `domicilios`

Datos y seguimiento de la entrega de un pedido.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador del domicilio. |
| `pedido_id` | `INT` | FK lógica | Pedido asociado. Algunas rutas antiguas admiten `id_pedido`. |
| `direccion` | `VARCHAR` / `TEXT` | — | Dirección de entrega. |
| `telefono` | `VARCHAR` | Opcional según flujo | Teléfono de contacto para la entrega. |
| `estado` | `VARCHAR` | — | Estado operativo: `pendiente`, `en camino` o `entregado`. |
| `repartidor` | `VARCHAR` | Puede ser `NULL` | Nombre del repartidor asignado; el código actual usa texto y no un identificador de usuario. |
| `nombre` | `VARCHAR` | Opcional según esquema | Nombre de contacto guardado junto al domicilio, si la columna existe. |
| `pago` | `VARCHAR` | Opcional según esquema | Método de pago replicado en instalaciones que incluyen esta columna. |
| `observaciones` | `TEXT` / `VARCHAR` | Opcional según esquema | Instrucciones de entrega, si la columna existe. |

## Tabla `reservas`

Reservas de mesa realizadas por clientes.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador de la reserva. |
| `usuario_id` | `INT` | FK lógica | Usuario propietario de la reserva. |
| `nombre` | `VARCHAR` | Opcional en algunos flujos | Nombre de contacto de la reserva. |
| `telefono` | `VARCHAR` | Opcional en algunos flujos | Teléfono de contacto. |
| `fecha` | `DATE` | — | Día de la reserva. |
| `hora` | `TIME` | — | Hora de la reserva. |
| `personas` | `INT` | — | Cantidad de personas. La validación de la aplicación admite entre 1 y 20. |
| `observaciones` | `TEXT` / `VARCHAR` | Opcional | Alergias, celebraciones u otras solicitudes. |
| `estado` | `VARCHAR` | — | Estado del ciclo de reserva; el código usa `pendiente` y `cancelada`, entre otros. |

## Tabla `password_resets`

Solicitudes de recuperación de contraseña.

| Campo | Tipo de referencia | Clave o regla | Descripción |
|---|---|---|---|
| `id` | `INT` | PK | Identificador de la solicitud. |
| `usuario_id` | `INT` | FK lógica | Usuario que solicitó el restablecimiento. |
| `token_hash` | `CHAR(64)` / `VARCHAR` | Hash | Hash SHA-256 del token; el token original se envía al usuario y no se almacena. |
| `expira_en` | `DATETIME` | — | Fecha y hora hasta la que el token es válido. |
| `usado_en` | `DATETIME` | Puede ser `NULL` | Fecha y hora en que se consumió el token; `NULL` indica que no se ha usado. |

## Notas de implementación

- El pedido almacena los productos como JSON en `pedidos.productos`. Aunque el diagrama lógico del repositorio representa una entidad `detalle_pedido`, no se observó esa tabla en el flujo actual de compra.
- Las relaciones marcadas como lógicas se deducen de las consultas y el uso de identificadores; deben contrastarse con las claves foráneas reales del servidor MySQL.
- Algunas rutas conservan compatibilidad con `id_usuario` en `pedidos` y `id_pedido` en `domicilios`. Se recomienda estandarizar en `usuario_id` y `pedido_id` después de confirmar el esquema instalado.
- El diagrama lógico muestra algunos nombres distintos de los usados por el código actual. Para modificar la base de datos, prevalece la definición física vigente.
- La tabla `productos` puede incluir columnas adicionales en el esquema o en instalaciones previas, como una ruta de imagen. No se incorpora aquí porque el código actual del catálogo utiliza assets estáticos y no consulta una columna de imagen.

## Fuentes del proyecto

- `php/modelo/conexion.php` y `php/configuracion/verificacion.php`
- `php/controlador/registro.php`, `php/controlador/domicilios/` y `php/controlador/reservas/`
- `api/registro.php`, `api/domicilios.php`, `api/reservas.php` y `api/password_recovery.php`
- `paginas/admin/configuracion/` y `paginas/repartidor/`
- `diagramas_la_pesquera/modelo_logico_la_pesquera.png`
