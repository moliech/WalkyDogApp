# WalkyDog 🐾

**WalkyDog** es una plataforma web responsiva orientada a la gestión y auditoría del servicio de paseo de perros. Permite que los propietarios agenden servicios y monitoreen en tiempo real la ruta de sus mascotas, mientras los paseadores actualizan su posición y registran novedades a través de códigos QR.

---

## 🚀 Estado del Proyecto (Módulo III - ORM Eloquent y Autenticación)
En esta etapa, el proyecto ha completado su integración con base de datos relacional MySQL usando Eloquent, implementado autenticación segura de usuarios mediante **Laravel Breeze** (Blade stack) y desarrollado 4 flujos dinámicos interactivos de negocio.

### 1. Sistema de Autenticación y Perfiles Condicionales (Breeze)
- **Cuentas y Seguridad**: Registro, inicio de sesión y recuperación de contraseñas 100% en español.
- **Campos Especializados**: Se adaptó el esquema para registrar `nombres`, `apellidos`, `telefono` y `direccion` de residencia.
- **Tipos de Cuenta**: Al registrarse, el usuario define si es **Propietario** (para registrar mascotas y agendar paseos) o **Paseador** (quien entra en estado `'pendiente'` hasta ser aprobado por un Administrador).
- **Control de Vistas por Rol**:
  * **Propietarios**: Ven *Dashboard*, *Mis Mascotas*, *Monitoreo*, *Historial de Pagos* y el botón *Agendar Paseo*.
  * **Paseadores**: Ven *Dashboard* y *Paseador* (su agenda).
  * **Administradores**: Acceso a la sección de **Auditoría** para activar o rechazar postulantes.

### 2. Estructura Relacional (ORM Eloquent)
Se implementó el esquema físico relacional en la base de datos en la 3ra Forma Normal (3FN):
- **`User`**: Relación 1:N con `mascotas`, 1:1 con `paseadores_perfiles` y 1:N con `paseos` (como paseador).
- **`Mascota`**: Pertenece a un propietario y tiene muchos paseos. Cuenta con Scopes Locales de filtrado (`scopeBuscar` y `scopePorTamano`).
- **`PaseadorPerfil`**: Contiene la documentación de soporte del paseador (cédula, experiencia y estado).
- **`Paseo`**: Registro central que asocia mascota, paseador, estado del recorrido y token QR.
- **`Pago`**: Almacena el estado financiero del cobro por horas.
- **`Ubicacion`**: Historial geográfico de coordenadas para trazar el mapa de ruta.
- **`Novedad`**: Bitácora de incidentes reportados en el trayecto.

### 3. Los 4 Flujos de Negocio Dinámicos
* **Agendamiento y Pagos**: El propietario selecciona su mascota y paseador. Se crea el paseo (`'programado'`) y pago (`'pending'`). Al autorizar en la pasarela simulada, el pago cambia a `'approved'`.
* **Control del Paseador**: Carlos Mendoza ve sus paseos en su agenda. Puede *Iniciar Paseo* (registra hora de inicio y cambia a `'en_progreso'`), reportar *Novedades* escritas, y *Finalizar Recorrido* (estado `'finalizado'`).
* **Monitoreo en Tiempo Real**: Carga el mapa de **Leaflet.js** y **OpenStreetMap** dibujando la polilínea del recorrido real de la mascota. Cuenta con un selector dinámico arriba si hay más de un paseo activo.
* **Auditoría de Administrador**: Permite aprobar/rechazar postulaciones de paseadores pendientes para cambiarlos a estado `'activo'`.

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

## 🌐 URLs de Acceso Local
* **Aplicación Laravel**: [http://localhost:8060](http://localhost:8060)
* **Base de Datos MySQL**: Host `localhost` en el puerto `3308`.

---
✨ **Desarrollado para el Proyecto de Grado - Seminario Laravel.**
