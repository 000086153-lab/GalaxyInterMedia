# GalaxyInterMedia CRM

Landing page en PHP con persistencia MySQL y dashboard administrativo para las
solicitudes comerciales de GalaxyInterMedia.

## Funciones

- Formulario con validación en navegador y servidor.
- Protección CSRF, campo antispam y límite básico entre envíos.
- Persistencia MySQL con consultas preparadas.
- Dashboard privado con métricas, búsqueda, filtros y paginación.
- Estados de seguimiento: Nuevo, Contactado, En seguimiento, Cerrado y Descartado.
- Exportación CSV protegida y compatible con Excel.
- Contenedor Docker listo para Railway.

## Variables de entorno

La aplicación acepta las variables cortas `DB_*` y también las variables
`MYSQL*` que Railway genera para su servicio MySQL.

| Variable | Uso |
| --- | --- |
| `DB_HOST` o `MYSQLHOST` | Host privado de MySQL |
| `DB_PORT` o `MYSQLPORT` | Puerto de MySQL |
| `DB_NAME` o `MYSQLDATABASE` | Nombre de la base |
| `DB_USER` o `MYSQLUSER` | Usuario de la base |
| `DB_PASSWORD` o `MYSQLPASSWORD` | Contraseña de la base |
| `ADMIN_USER` o `ADMIN_EMAIL` | Usuario para el dashboard |
| `ADMIN_PASSWORD_HASH` | Hash creado con `password_hash()` |
| `ADMIN_PASSWORD` | Compatibilidad con una contraseña existente en Railway |
| `APP_TIMEZONE` | Zona horaria; por defecto `America/Mexico_City` |

No subas `config.php`, `.env` ni credenciales al repositorio.

## Rutas

- `/` — landing page.
- `/guardar.php` — endpoint del formulario.
- `/admin/login.php` — acceso al dashboard.
- `/health.php` — comprobación de aplicación y base de datos.

La tabla se crea automáticamente en la primera conexión. También se incluye
`database/database.sql` como referencia y para inicialización manual.

