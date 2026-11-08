# Instalación desde Git, XAMPP y MariaDB

Esta carpeta contiene una copia completa del código web de GLPI Fundación Luz 12.0.1. La base institucional, contraseñas, claves de cifrado y documentos adjuntos se entregan aparte. No los subas al repositorio.

## 1. Clonar el proyecto

Abre PowerShell en la carpeta donde guardarás el proyecto:

```powershell
git clone https://github.com/Danielsaan9/Practica-informatica.git
cd Practica-informatica\inventario
```

El repositorio es privado; inicia sesión mediante Git Credential Manager cuando GitHub lo solicite. No pegues contraseñas ni tokens de Git en el comando o en un archivo del proyecto.

## 2. Copiar GLPI a XAMPP

1. Abre XAMPP Control Panel. Asegúrate de tener Apache y MySQL/MariaDB detenidos mientras haces la copia.
2. Usa una carpeta de destino vacía. El script no sobrescribe instalaciones existentes:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\instalar-local.ps1 -GlpiRoot C:\xampp\htdocs\glpi
```

3. Si `C:\xampp\htdocs\glpi` ya tiene la instalación que quieres conservar, no uses el script allí: crea una carpeta nueva (por ejemplo `C:\xampp\htdocs\glpi-fundacion`) y apunta el sitio de Apache a su carpeta `public`.
4. Revisa la versión de PHP y extensiones contra los requisitos de GLPI 12.0.1. Fuente oficial: https://glpi-install.readthedocs.io/en/latest/prerequisites.html
5. Configura Apache con raíz web en `...\glpi\public`. Para un entorno personal de prueba, usa la URL local y el puerto realmente configurado en XAMPP, por ejemplo `http://127.0.0.1:8088/`. No expongas el directorio completo de GLPI a Internet.

## 3. Encender servicios

1. Abre **XAMPP Control Panel** desde el menú Inicio.
2. Presiona **Start** en Apache y **Start** en MySQL. MySQL de XAMPP emplea MariaDB.
3. Comprueba que ambos aparezcan activos y abre la URL local de la aplicación.
4. Al terminar, cierra sesión y presiona **Stop** en los dos servicios. No detengas MySQL durante una importación o actualización.

Si Apache no inicia, revisa **Logs → Apache** y el puerto configurado. Si MySQL no inicia, revisa **Logs → MySQL** y conflictos de puerto.

## 4. Crear o restaurar la base

### Restaurar una base ya existente

1. Obtén el `.sql` oficial por un canal privado.
2. En XAMPP, inicia MySQL y abre `http://127.0.0.1/phpmyadmin` (con puerto si aplica).
3. Crea una base vacía, por ejemplo `glpi_fundacion`, con `utf8mb4_unicode_ci`.
4. Selecciona la base → **Importar** → elige el dump correcto → **Importar**. Si phpMyAdmin no acepta el tamaño, usa el cliente `mariadb`/`mysql` incluido en XAMPP o aumenta temporalmente los límites de PHP y reinicia Apache.
5. Copia el `config_db.php` correspondiente al respaldo a `C:\xampp\htdocs\glpi\config\config_db.php`, o ejecuta el asistente de GLPI y usa las credenciales MariaDB proporcionadas por el administrador. La conexión local normalmente usa host `127.0.0.1`, pero confirma el puerto, nombre de base, usuario y contraseña reales.

La clave `config\glpicrypt.key` debe provenir del mismo respaldo confiable si se necesita descifrar credenciales de integraciones. Nunca la subas a Git. Sin esa clave, vuelve a configurar las credenciales de integraciones desde GLPI.

### Instalación nueva

Abre la URL de GLPI. En el instalador, selecciona MariaDB, escribe host, usuario, contraseña y base, y deja que GLPI cree las tablas. Después inicia sesión con una cuenta administrativa individual.

**No importes un dump sobre una base ya usada**. Haz respaldo primero. No cambies ni mezcles la base institucional con datos de demostración.

## 5. Activar el plugin Fundación Luz

Entra como administrador: **Configuración → Plugins → Fundación Luz → Instalar → Activar**. La instalación del plugin crea su tabla propia de valoraciones mediante su ciclo normal de instalación. No uses dumps SQL internos ni limpies tablas existentes.

Para la consulta de mercado, un administrador configura el token desde la página del plugin. Se cifra con `glpicrypt.key`; el token y la llave nunca se guardan en Git.

## 6. Acceso desde casa o desde el equipo

Para que otra persona use la misma instancia, necesita estar en la VPN Tailscale autorizada, que este computador esté encendido, y Apache/MariaDB activos. Comparte URL Tailscale y cuenta individual de GLPI. Cambiar manualmente su dirección IP no crea acceso remoto. No envíes el puerto de MariaDB, la clave de cifrado ni las credenciales de base de datos.

## Respaldo y fuentes

Respalda juntos la base de datos, carpeta `files/` (documentos adjuntos) y `config/` (incluyendo la llave) en almacenamiento privado. El repositorio Git es para código.

- Instalación oficial: https://glpi-install.readthedocs.io/en/latest/install/
- Requisitos oficiales: https://glpi-install.readthedocs.io/en/latest/prerequisites.html
- Versión GLPI 12.0.1: https://github.com/glpi-project/glpi/releases/tag/12.0.1
