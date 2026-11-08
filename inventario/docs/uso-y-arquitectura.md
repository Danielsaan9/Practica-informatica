# Funcionamiento, uso y arquitectura

## Componentes

La carpeta `app/glpi` contiene el código completo de la versión GLPI 12.0.1 trabajada en el computador: núcleo, bibliotecas, vistas, estilos, plugin Fundación Luz y archivos personalizados. El directorio público que sirve Apache es `app/glpi/public`. Git guarda el código y su evolución; no es la base de datos.

XAMPP ejecuta Apache (servidor web), PHP (lógica de GLPI) y MariaDB (registros). GLPI valida identidad y perfil, ejecuta lógica PHP, consulta o actualiza MariaDB y devuelve HTML/JS/CSS al navegador. Los archivos adjuntos y datos temporales se guardan en `files/`, por fuera del código versionado.

## Funciones visibles

- **Dashboard general:** resume activos y trabajo operativo de GLPI.
- **Activos:** computadores y otros tipos habilitados muestran identificación, fabricante, modelo, serie, ubicación, responsable y relaciones disponibles.
- **Contactos y trabajadores:** se administran con permisos por perfil y entidad; la información personal requiere acceso limitado.
- **Dashboard económico:** plugin Fundación Luz. Distingue costo de compra registrado de reposición estimada en el mercado, con fecha y fuente.
- **Detalle del computador:** muestra los campos económicos disponibles para ese equipo y la referencia de mercado consultada.

## Flujo de datos del dashboard económico

1. El valor de compra procede del campo guardado en el registro de GLPI.
2. Para estimar reposición, el plugin identifica modelo/fabricante y consulta publicaciones de Mercado Libre Chile cuando se configuró un token de API.
3. Descarta resultados que no sean nuevos, no estén en CLP o no coincidan con el modelo consultado; calcula una mediana de precios comparables.
4. Guarda valor, fuente, fecha y contexto del modelo en una tabla del plugin para presentar trazabilidad.
5. La mediana es un indicador de reposición observado, no un avalúo ni un valor contable certificado. Estado, antigüedad, configuración, disponibilidad y comparabilidad pueden variar.

Costo registrado responde cuánto se pagó según el dato de compra. Reposición estimada responde cuánto cuesta encontrar una unidad comparable actualmente. Deben mantenerse separados y sus fuentes/fechas deben ser visibles. La depreciación contable necesita política aprobada, fecha de adquisición, vida útil y método; no se deduce automáticamente de una consulta de precios.

## Permisos

GLPI controla acciones por usuario, perfil, entidad y derechos de lectura/creación/actualización. Usa usuarios personales, asigna el perfil mínimo necesario y prueba el acceso con una cuenta de cada grupo antes de compartir. La autorización de VPN/Tailscale no da por sí sola derechos dentro de GLPI; son controles independientes.

## Tareas diarias de inventario

1. Busca primero por número de inventario, serie y nombre para reducir duplicados.
2. Completa marca, modelo, número de serie, responsable, ubicación, estado y fecha de compra disponibles.
3. Asocia cargador u otro accesorio únicamente cuando la relación sea verificable y útil.
4. Adjunta respaldo de compra donde la política institucional lo permita.
5. Registra movimientos como cambios de estado o ubicación; evita borrar registros que deban conservar historial.
6. Revisa que el dashboard coincida con filtros y entidad de tu perfil. Los conteos no deben sumarse si las categorías se traslapan.

## Por qué esta estructura

- Mantener el core GLPI y el plugin dentro de `app/glpi` hace sencillo clonar y revisar exactamente la versión trabajada.
- Separar `config/`, `files/` y la base de Git evita publicar credenciales, datos personales, documentos y sesiones. En producción, GLPI recomienda que Apache apunte a `public`, protegiendo los archivos de configuración.
- Usar la estructura estándar GLPI hace que el equipo reconozca las rutas de plugin, vistas, estilos y dependencias.
- Los cambios deben compararse con el GLPI oficial antes de actualizar. Las modificaciones directas al core pueden requerir reaplicarse o adaptarse en futuras versiones; cuando sea viable, una función debería vivir en un plugin para reducir el costo de mantenimiento.

## Seguridad y continuidad

- No versionar `.sql`, `config_db.php`, `glpicrypt.key`, `config_stage`, tokens, `files/`, logs ni respaldos.
- Guardar base, `files/` y `config/` en respaldos privados y coherentes entre sí.
- No usar cuentas compartidas ni enviar credenciales por el repositorio.
- Antes de cambiar core, permisos o esquema: respaldo; prueba en instancia aislada; prueba de permisos; después paso controlado a la instancia del equipo.
- Mantener XAMPP local como entorno de prueba. Para operación permanente multiusuario, el equipo debe definir una instancia siempre encendida y controles de red apropiados.
