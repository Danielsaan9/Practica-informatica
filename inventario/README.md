# GLPI Fundación Luz

Copia completa de la aplicación web trabajada para Fundación Luz, basada en **GLPI 12.0.1**, con plugin de valorización, dashboard económico, cambios de interfaz, recursos de marca y las guías del proyecto.

## Contenido

- `app/glpi/`: código ejecutable completo de la versión que está en XAMPP, incluidas sus personalizaciones.
- `docs/instalacion.md`: descarga desde Git, instalación en XAMPP y configuración/restauración privada de MariaDB.
- `docs/uso-y-arquitectura.md`: operación, datos, permisos, dashboard y justificación técnica.
- `scripts/instalar-local.ps1`: instala el código en una carpeta GLPI vacía; se detiene si encuentra archivos para no pisar otra instalación.
- `assets/brand/`: originales gráficos institucionales.

## Separación de datos y código

Por seguridad y para mantener Git liviano, este repositorio no incluye la base SQL, `config/`, `config_stage/`, `files/` (adjuntos, caché, logs y sesiones), tokens ni llaves de cifrado. Esas piezas se respaldan y transfieren de forma privada. La aplicación crea nuevas carpetas vacías para configuración y archivos al instalar.

## Instalación breve

1. Clona el repositorio privado.
2. En `inventario`, ejecuta `powershell -ExecutionPolicy Bypass -File .\scripts\instalar-local.ps1 -GlpiRoot C:\xampp\htdocs\glpi`.
3. Enciende Apache y MySQL en XAMPP; completa el asistente GLPI o restaura y conecta la base autorizada.
4. Activa el plugin **Fundación Luz** en Configuración → Plugins.

Revisa `docs/instalacion.md` y `docs/uso-y-arquitectura.md` antes de ponerlo en uso compartido. Conserva las licencias y avisos de autoría de GLPI.
