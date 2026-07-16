# WalkyDog 🐾

**WalkyDog** es una plataforma web responsiva orientada a la gestión y auditoría del servicio de paseo de perros. Permite que los propietarios agenden servicios y monitoreen en tiempo real la ruta de sus mascotas, mientras los paseadores actualizan su posición y registran novedades a través de códigos QR.

---

## 🚀 Estado del Proyecto (Módulo IV - Seguridad y APIs)
En esta etapa, el proyecto ha completado la implementación del Módulo IV (Seguridad y APIs), incorporando un sistema robusto de control de accesos, estandarización de respuestas API, geolocalización en tiempo real, validación interactiva por QR y consumo defensivo de servicios externos.

### 1. Seguridad, Autenticación y Control de Accesos
- **Roles y Username en la BD:** Modificación de la tabla de usuarios agregando los campos `username` y `rol` (`admin`, `paseador`, `propietario`). Los usuarios existentes fueron poblados con roles de acuerdo a sus perfiles.
- **Middleware de Roles:** Diseño del middleware `VerificarRol` para restringir el acceso a rutas según el rol asignado, soportando tanto guard web (sesiones) como API (JWT).
- **Políticas de Acceso:** Uso de `MascotaPolicy` para asegurar que los propietarios solo puedan interactuar con sus mascotas y los administradores puedan auditar todo.

### 2. Estandarización de la API de Mascotas (API Resources)
- **API Resources:** Creación de `MascotaResource` para estructurar y estandarizar las respuestas JSON de la API, ocultando marcas de tiempo internas y formateando la relación con el propietario.
- **Refactorización de Respuestas:** Adaptación de `Api\MascotaController` para retornar datos utilizando el nuevo recurso en lugar de modelos planos.

### 3. Geolocalización Global en Tiempo Real y Mapas
- **Historial de Coordenadas:** API REST bajo `/api/paseos/{id}/ubicacion` con control de frecuencia (*throttling*) para guardar coordenadas como máximo cada 15 segundos para no saturar la base de datos.
- **Seguimiento GPS Global:** Integración en la plantilla base (`layouts/app.blade.php`) de un rastreador en segundo plano que mantiene la pantalla activa (*Screen Wake Lock API*) y continúa transmitiendo las coordenadas del paseador en cualquier pestaña del sitio.
- **Soporte de Múltiples Paseos:** El script global detecta y transmite la ubicación para todos los paseos activos simultáneamente si el paseador pasea más de un perro a la vez.
- **Monitoreo Global (Admin):** Panel administrativo con filtro rápido por paseador que permite visualizar en tiempo real en mapas de **Leaflet.js** y **OpenStreetMap** la trayectoria de cualquier mascota activa.

### 4. Validación por Código QR
- **Generación de Código QR:** El propietario genera un código QR dinámico con el token único de inicio de paseo desde su panel de control.
- **Escaneo por Cámara:** El paseador activa su cámara móvil para escanear el QR del propietario, lo que valida el token en el backend e inicia el paseo de forma segura.

### 5. Integración de API Externa
- **Servicio Cliente HTTP:** Creación de `PostService` para realizar llamadas externas con control de timeouts a `jsonplaceholder.typicode.com`.
- **Diseño Defensivo:** Vista de publicaciones con control de errores que muestra una advertencia en rojo y permite el funcionamiento del sitio si el servidor externo no responde.

---

## 🔑 Credenciales de Prueba (Sembradas en BD)

Para ingresar y probar la plataforma con datos dinámicos, usa las siguientes cuentas:

| Rol | Correo Electrónico | Contraseña | Datos Sembrados |
| :--- | :--- | :--- | :--- |
| **Propietario / Admin** | `esteban.molina@cotecnova.edu.co` | `password` | Tiene 3 mascotas: Toby, Luna y Rambo. Un paseo activo de Toby. |
| **Paseador** | `carlos@demo.com` | `password` | Tiene asignado el paseo activo en curso de Toby. |

---

## 🛠️ Instrucciones de Ejecución Local

Para levantar el entorno de desarrollo y poblar la base de datos:

1. **Asegúrate de tener Docker Desktop ejecutándose** en tu máquina.
2. Levanta los contenedores en segundo plano:
   ```bash
   ./vendor/bin/sail up -d
   ```
3. Instala las dependencias y compila los recursos de frontend (Tailwind CSS v4 y fuentes):
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```
4. Ejecuta las migraciones y siembra los datos de prueba iniciales:
   ```bash
   ./vendor/bin/sail php artisan migrate:fresh --seed
   ```
5. Si deseas ejecutar el servidor de desarrollo de Vite para cambios en vivo:
   ```bash
   ./vendor/bin/sail npm run dev
   ```
6. Para detener los contenedores:
   ```bash
   ./vendor/bin/sail down
   ```

---

## 🔌 API REST (Módulo IV - JWT & Scramble Docs)

El proyecto incluye una API REST securizada con **JSON Web Tokens (JWT)** y documentación interactiva autogenerada mediante **Scramble**.

### 📖 Documentación Interactiva (Swagger UI)
Una vez levantado el proyecto, puedes ingresar al panel de documentación interactiva para explorar y probar cada uno de los endpoints en:
👉 **[http://localhost:8060/docs/api](http://localhost:8060/docs/api)**

### 🔐 Seguridad y Autenticación
Los endpoints protegidos requieren enviar el token JWT en las cabeceras HTTP de la siguiente manera:
```http
Authorization: Bearer <tu_token_jwt>
Accept: application/json
```

### 🛣️ Endpoints Disponibles

#### 🔑 Autenticación (Auth)
*   `POST /api/register` - Registro de nuevos clientes.
*   `POST /api/login` - Inicio de sesión y obtención del token JWT.
*   `POST /api/logout` - Cierre de sesión e invalidación del token.
*   `GET /api/me` - Obtención de la información del usuario autenticado (Protegido).

#### 🐾 Gestión de Mascotas (Mascotas CRUD - Protegido)
*   `GET /api/mascotas` - Listar mascotas (Los clientes ven solo las suyas; el Administrador audita todas).
*   `POST /api/mascotas` - Registrar una nueva mascota (Exclusivo para clientes).
*   `GET /api/mascotas/{id}` - Ver detalle de una mascota específica.
*   `PUT /api/mascotas/{id}` - Actualizar datos de la mascota (Solo propietario).
*   `DELETE /api/mascotas/{id}` - Eliminar mascota del sistema (Solo propietario).

---

## 🌐 URLs de Acceso Local
* **Aplicación Laravel**: [http://localhost:8060](http://localhost:8060)
* **Documentación API (Scramble)**: [http://localhost:8060/docs/api](http://localhost:8060/docs/api)
* **Base de Datos MySQL**: Host `localhost` en el puerto `3308`.

---
✨ **Desarrollado para el Proyecto de Grado - Seminario Laravel.**
