## Sistema de Punto de Venta (POS) Web

_Desarrollado con CodeIgniter 4 + MySQL + Bootstrap_
📌 Descripción del Proyecto
Sistema de punto de venta web completo construido con CodeIgniter 4, que incluye:

Gestión de productos/inventario

Procesamiento de ventas y transacciones

Reportes y análisis de ventas

Control de usuarios y permisos

🚀 Tecnologías Principales
Componente Versión/Detalle
Backend CodeIgniter 4 (PHP 8.1+)
Base de Datos MySQL 8.x
Frontend Bootstrap 4.5 + jQuery UI 1.13
Librerías SB Admin 7.0.7, SweetAlert2
⚙️ Requisitos del Servidor
PHP ≥ 8.1 con extensiones: intl, mbstring, mysqlnd, json

MySQL ≥ 5.7 o MariaDB ≥ 10.3

Composer (para dependencias)

Servidor web (Apache/Nginx) configurado con:

apache
DocumentRoot "/ruta/al/proyecto/public"
🔧 Instalación
Clonar repositorio:

bash
git clone https://github.com/tu-usuario/tu-pos-ci4.git
cd tu-pos-ci4
Instalar dependencias:

bash
composer install
Configurar entorno:

bash
cp env .env
nano .env # Editar credenciales de DB y configuraciones
Migrar base de datos:

bash
php spark migrate --all
🌟 Características Clave
Interfaz responsive con SB Admin Dashboard

Ventas rápidas con búsqueda AJAX de productos

Reportes en tiempo real con gráficos interactivos

Multi-usuario con roles y permisos

Notificaciones con SweetAlert2

📚
