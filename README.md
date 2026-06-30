# WalkyDog 🐾

**WalkyDog** es una plataforma web responsiva orientada a la gestión y auditoría del servicio de paseo de perros. Permite que los propietarios monitoreen en tiempo real la ruta de sus mascotas, mientras los paseadores actualizan su posición y certifican el servicio a través de códigos QR.

---

## 🚀 Estado del Proyecto (Módulo II - Rutas, Vistas y Controladores)
En esta etapa, el proyecto ha evolucionado incorporando una arquitectura completa de interacción con rutas mapeadas, controladores funcionales con datos simulados (mock) y una interfaz de usuario pulida y responsiva.

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

### 3. Interfaz de Usuario y Estilos (`resources/views/` & `public/css/`)
* **Diseño Responsivo**: Basado en **Bootstrap 5.3** y estilizado de forma personalizada con **Plus Jakarta Sans** como fuente moderna.
* **Estilo Personalizado (`custom.css`)**: Implementa una paleta de colores cálidos (cálido crema, carbón, naranja acogedor), tarjetas con esquinas redondeadas y sombras suaves, animaciones de pulso para paseos activos y estilos tipo ticket para el recibo de pago.
* **Flujos Simulados Interactivos**:
  - Modal dinámico de **Agendar Paseo** para programar paseos simulados.
  - Simulación de geolocalización del paseo mediante un marcador visual sobre un mapa interactivo.
  - Simulación de validación mediante códigos QR y redirección automática hacia la pasarela de pagos.

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
