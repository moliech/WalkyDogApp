# WalkyDog 🐾

**WalkyDog** es una plataforma web responsiva orientada a la gestión y auditoría del servicio de paseo de perros. Permite que los propietarios monitoreen en tiempo real la ruta de sus mascotas, mientras los paseadores actualizan su posición y certifican el servicio a través de códigos QR.

---

## 🚀 Estado del Proyecto (Módulo II - Rutas, Vistas y Controladores)
En esta etapa, el proyecto ha evolucionado incorporando una arquitectura de interacción con rutas mapeadas, controladores funcionales con datos simulados (mock) y una interfaz de usuario responsiva maquetada con **Tailwind CSS**.

### 1. Sistema de Rutas y Navegación
Se configuraron los siguientes endpoints clave en `routes/web.php`:
* **Dashboard (`/`)**: Vista principal con estadísticas generales del sistema.
* **Mis Mascotas (`/mascotas`)**: Listado de mascotas registradas por el usuario.
* **Monitoreo (`/paseos/monitoreo`)**: Interfaz de seguimiento en tiempo real del paseo activo.
* **Paseador (`/paseos/control`)**: Panel de control para el paseador con validación del servicio.
* **Editar Perfil (`/perfil/editar`)**: Formulario con información personal del usuario.
* **Simulación de Pago (`/pagos/simulacion/{id}`)**: Recibo digital de cobro y simulación de la transacción.

### 2. Capa de Controladores (`app/Http/Controllers/`)
Se implementó lógica de respuesta con matrices de datos simulados:
* **`DashboardController`**: Retorna métricas clave (paseos activos, mascotas totales, paseadores disponibles) y datos simulados del perfil de usuario.
* **`MascotaController`**: Retorna un listado de mascotas de prueba (nombre, raza y tamaño).
* **`PaseoController`**: Provee los datos de geolocalización simulados (latitud, longitud), detalles del paseador y tarifas del servicio.

### 3. Interfaz de Usuario y Estilos (`resources/views/` & `resources/css/`)
* **Diseño Responsivo**: Maquetado en su totalidad con **Tailwind CSS v4** e integrado mediante Vite.
* **Estilo y Tematización**: El archivo `resources/css/app.css` tiene configurados los tokens de marca personalizados (`brand-primary`, `brand-secondary`, `brand-bg`, `brand-dark`).
* **Interactividad Híbrida**: Se utiliza un puente de compatibilidad mínima para que el JS de Bootstrap controle la interactividad de colapsables y modales (Navbar y "Agendar Paseo") antes de la integración completa con Laravel Breeze.

---

## 🛠️ Instrucciones de Ejecución Local

Para levantar el entorno de desarrollo utilizando Docker Sail y compilar los recursos de Tailwind CSS:

1. **Asegúrate de tener Docker Desktop ejecutándose** en tu máquina.
2. Levanta los contenedores en segundo plano:
   ```bash
   ./vendor/bin/sail up -d
   ```
3. Ejecuta el servidor de desarrollo de Vite para compilar Tailwind CSS en tiempo real:
   ```bash
   ./vendor/bin/sail npm run dev
   ```
   *(Si estás ejecutando localmente en Windows con Node instalado, puedes correr `npm run dev` en la raíz del proyecto)*.
4. Para detener los contenedores:
   ```bash
   ./vendor/bin/sail down
   ```

---

## 🌐 URLs de Acceso Local
* **Aplicación Laravel**: [http://localhost:8060](http://localhost:8060)
* **Base de Datos MySQL**: Host `localhost` en el puerto `3308`.

---
✨ **Desarrollado para el Proyecto de Grado - Seminario Laravel.**
