# WalkyDog 🐾

**WalkyDog** es una plataforma web responsiva orientada a la gestión y auditoría del servicio de paseo de perros. Permite que los propietarios monitoreen en tiempo real la ruta de sus mascotas, mientras los paseadores actualizan su posición y certifican el servicio a través de códigos QR.

---

## 🚀 Estado Actual del Proyecto (Primer Commit)
Esta primera versión establece el esqueleto base y el entorno del sistema:

1. **Estructura Base de Laravel**: Framework PHP configurado e inicializado.
2. **Entorno Contenedorizado con Docker (Laravel Sail)**:
   - Configuración de servicios en `compose.yaml`.
   - **Servidor Web (PHP 8.5/Sail)** mapeado en el puerto local **`8060`**.
   - **Base de Datos MySQL 8.4** mapeada en el puerto local **`3308`** (base de datos: `walkydog`, usuario: `sail`, contraseña: `password`).
3. **Controladores Iniciales**:
   - `DashboardController`: Gestión de vistas según el rol de usuario.
   - `MascotaController`: Controladores para el CRUD de mascotas.
   - `PaseoController`: Lógica de paseos y actualización de rutas.

---

## 🛠️ Instrucciones de Ejecución Local

Para levantar el entorno de desarrollo utilizando Docker Sail, sigue estos pasos:

1. **Asegúrate de tener Docker Desktop ejecutándose** en tu máquina.
2. Abre la terminal en el directorio raíz del proyecto y levanta los contenedores en segundo plano:
   ```bash
   ./vendor/bin/sail up -d
   ```
   *(Si estás ejecutando directamente desde Windows y tienes configurado Docker Compose, puedes usar `docker compose up -d`)*.
3. Para detener el entorno:
   ```bash
   ./vendor/bin/sail down
   ```

---

## 🌐 URLs de Acceso Local
* **Aplicación Laravel**: [http://localhost:8060](http://localhost:8060)
* **Base de Datos MySQL**: Host `localhost` en el puerto `3308`.

---
✨ **Desarrollado para el Proyecto de Grado - Seminario Laravel.**
