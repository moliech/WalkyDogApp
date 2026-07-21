# WalkyDog 🐾
### Plataforma de Gestión, Auditoría y Monitoreo en Tiempo Real para Paseo de Mascotas

**WalkyDog** es una solución digital de nivel empresarial diseñada para optimizar, auditar y gestionar servicios profesionales de paseo de perros. La plataforma conecta de manera fluida a propietarios de mascotas y paseadores certificados, proporcionando monitoreo de rutas por GPS satelital en tiempo real, validación física del inicio del recorrido por códigos QR dinámicos y un sólido panel de control administrativo para supervisar las operaciones y transacciones financieras.

---

## 🌟 Características Principales

### 🔒 1. Seguridad de Extremo a Extremo y Control de Acceso (RBAC)
*   **Gestión Basada en Roles (RBAC):** Restricciones de rutas mediante middlewares avanzados para los roles de **Administrador**, **Paseador** y **Propietario**, tanto en la capa web (sesiones) como en la API de consumo (JWT).
*   **Arquitectura de Políticas de Seguridad:** Aislamiento de datos de clientes y mascotas a nivel de base de datos utilizando Laravel Policies, asegurando la privacidad total del usuario.
*   **Autenticación de Dos Factores (OTP):** Mayor seguridad en el inicio de sesión y operaciones críticas mediante códigos OTP enviados por canales seguros.

### 📍 2. Monitoreo Satelital por GPS en Tiempo Real
*   **Transmisión en Segundo Plano:** Motor de localización integrado en el navegador (*Screen Wake Lock API* e *HTML5 Geolocation API*) que transmite las coordenadas del paseo continuamente, incluso con el dispositivo móvil bloqueado o en pestañas secundarias.
*   **Optimización de Datos:** Envío de coordenadas controlado mediante políticas de frecuencia (*throttling*) para evitar el consumo excesivo de batería y optimizar el almacenamiento de trayectorias.
*   **Monitoreo Multitarea:** Soporte para paseos múltiples en simultáneo, permitiendo al paseador reportar coordenadas concurrentes por cada mascota.
*   **Auditoría y Mapas Interactivos:** Panel interactivo administrativo integrado con **Leaflet.js** y **OpenStreetMap** para renderizar la trayectoria en tiempo real de cualquier recorrido activo.

### 📲 3. Validación y Control por Código QR
*   **Código QR Dinámico:** El dueño de la mascota genera un token QR único y temporal asociado al paseo programado.
*   **Escaneo Seguro:** El paseador inicia el recorrido escaneando el código QR del propietario a través de la cámara de su dispositivo móvil, lo que valida la autenticidad e inicia el conteo del paseo en el backend.

### 💰 4. Gestión de Tarifas e Historial de Pagos
*   **Recargos Dinámicos:** Los paseadores destacados (con alta calificación promedio de sus paseos calificados) pueden aplicar recargos opcionales sobre la tarifa base de acuerdo a la configuración del sistema.
*   **Auditoría de Transacciones:** Exportación del historial financiero a formato PDF profesional para facilitar reportes mensuales.

---

## 🛠️ Stack Tecnológico
*   **Backend:** Laravel (PHP) con base de datos MySQL.
*   **Frontend:** Vanilla CSS con TailwindCSS para estilos premium.
*   **Mapas y Geolocalización:** Leaflet.js con OpenStreetMap.
*   **Autenticación API:** JSON Web Tokens (JWT) mediante `tymon/jwt-auth`.
*   **Documentación API:** Scramble (Swagger/OpenAPI compatible).

---

## 💻 Instrucciones de Instalación y Ejecución Local

Para levantar el entorno de desarrollo contenerizado utilizando Docker y Laravel Sail:

### 1. Requisitos Previos
*   Tener **Docker Desktop** instalado y en ejecución en el sistema.

### 2. Inicialización de Contenedores
Ejecuta el entorno en segundo plano:
```bash
./vendor/bin/sail up -d
```

### 3. Instalación de Dependencias y Compilación de Assets
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 4. Configuración de Base de Datos y Semillas
Ejecuta las migraciones y puebla la base de datos con los datos de prueba y perfiles por defecto:
```bash
./vendor/bin/sail php artisan migrate:fresh --seed
```

### 5. Servidor de Desarrollo (Vite)
Para activar la recarga rápida de cambios frontend en vivo:
```bash
./vendor/bin/sail npm run dev
```

---

## 🔌 API REST y Documentación Interactiva

WalkyDog expone una API REST robusta que puede ser consumida por aplicaciones móviles u otros servicios externos.

### 📖 Documentación Interactiva (Swagger/OpenAPI UI)
Puedes explorar, interactuar y probar todos los endpoints disponibles del sistema desde la UI autogenerada:
👉 **[http://localhost:8060/docs/api](http://localhost:8060/docs/api)**

### 🔐 Autenticación en la API
Los endpoints protegidos de la API requieren enviar el token JWT en las cabeceras HTTP de autorización:
```http
Authorization: Bearer <tu_token_jwt>
Accept: application/json
```

---

## 🌐 URLs de Acceso Local
*   **Aplicación Web**: [http://localhost:8060](http://localhost:8060)
*   **Documentación API**: [http://localhost:8060/docs/api](http://localhost:8060/docs/api)
*   **Base de Datos (MySQL)**: Host `localhost` en el puerto `3308`.

---
✨ **WalkyDog es un producto de software libre diseñado para la excelencia en la gestión del cuidado animal.**
