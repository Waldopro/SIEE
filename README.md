# SIEE - Sistema de Inscripción Escolar Estadístico

## Descripción

El **SIEE** (Sistema de Inscripción Escolar Estadístico) es una aplicación web diseñada para la gestión integral de procesos escolares en instituciones educativas venezolanas, específicamente para unidades educativas básicas y medias (UEBEM). Este sistema digitaliza y automatiza tareas administrativas y académicas, reemplazando procesos manuales con soluciones eficientes basadas en tecnología web.

El sistema permite la gestión de estudiantes, docentes, representantes y administradores, facilitando la inscripción, matrícula, evaluación académica y generación de reportes estadísticos. Está construido con PHP en el backend, MySQL como base de datos y tecnologías frontend modernas para una experiencia de usuario intuitiva.

## Características Principales

- **Gestión de Usuarios**: Registro y administración de estudiantes, docentes, representantes, padres y administradores con control de acceso basado en roles (Admin, Usuario, Docente).
- **Inscripción y Matrícula**: Proceso automatizado de inscripción de estudiantes nuevos y reinscripción, con control de cupos por curso y asignación automática.
- **Gestión Académica**: Creación y asignación de cursos, asignación de docentes, evaluaciones y generación de boletines (reportes de calificaciones).
- **Automatización**: Procesos automáticos para el cambio de año escolar, avance de estudiantes basado en rendimiento y actualización de cursos.
- **Reportes y Certificados**: Generación de constancias de inscripción, reportes estadísticos por año/sección y exportación a PDF.
- **Búsqueda y Listados**: Funcionalidades avanzadas para buscar estudiantes, inscritos, docentes y cursos.
- **Dashboard y Estadísticas**: Panel de control con visualizaciones de datos de matrícula y análisis estadísticos.
- **Seguridad**: Gestión de sesiones, validación de entradas, protección CSRF y auditoría de accesos.

## Tecnologías Utilizadas

- **Backend**: PHP 8.2+
- **Base de Datos**: MySQL 10.11+
- **Frontend**: HTML5, CSS3 (Bootstrap 4/5), JavaScript (jQuery, Chart.js)
- **Librerías**:
  - PHP: PhpSpreadsheet (manejo de Excel), TCPDF/mPDF/DomPDF (generación de PDFs), PHPUnit (pruebas)
  - JS: Firebase (notificaciones), xlsx-populate (procesamiento de Excel), Select2 (selectores avanzados), SweetAlert (alertas)
- **Dependencias**: Gestionadas con Composer (PHP) y npm (JavaScript)
- **Servidor**: Compatible con Apache/Nginx

## Problemas Reales que Resuelve

El sistema aborda desafíos comunes en la gestión educativa manual:

- **Ineficiencias en Inscripciones**: Reemplaza formularios en papel con procesos digitales, reduciendo errores y tiempo de procesamiento.
- **Gestión Académica Manual**: Automatiza la asignación de cursos, evaluaciones y generación de boletines, eliminando tareas repetitivas y mejorando la precisión.
- **Falta de Reportes**: Proporciona herramientas para generar estadísticas y certificados en tiempo real, facilitando el cumplimiento normativo y la toma de decisiones.
- **Escalabilidad y Seguridad**: Centraliza datos en una base de datos segura, permitiendo el crecimiento de la institución sin comprometer la integridad de la información.
- **Acceso Limitado**: Ofrece interfaces web accesibles para diferentes roles, mejorando la colaboración entre administradores, docentes y familias.

## Soluciones Proporcionadas

- **Automatización de Procesos**: Scripts que manejan transiciones anuales y avances estudiantiles, liberando tiempo para tareas pedagógicas.
- **Registros Digitales Centralizados**: Base de datos relacional para almacenar y consultar información de manera eficiente y segura.
- **Generación Automática de Documentos**: PDFs profesionales para constancias y reportes, listos para impresión o distribución digital.
- **Interfaz Intuitiva**: Diseños responsivos con navegación clara, adaptados a usuarios no técnicos.
- **Análisis de Datos**: Visualizaciones estadísticas para identificar tendencias y optimizar recursos escolares.

## Instalación

### Prerrequisitos
- PHP 8.2 o superior
- MySQL 10.11 o superior
- Composer (para dependencias PHP)
- npm (para dependencias JavaScript)
- Servidor web (Apache/Nginx)

### Pasos de Instalación
1. **Clona el repositorio**:
   ```bash
   git clone https://github.com/Waldopro/SIEE.git
   cd SIEE
   ```

2. **Instala dependencias PHP**:
   ```bash
   composer install
   ```

3. **Instala dependencias JavaScript** (si es necesario):
   ```bash
   npm install
   ```

4. **Configura la base de datos**:
   - Crea una base de datos MySQL.
   - Importa el esquema desde `db/escuela.sql`.
   - Actualiza las credenciales de conexión en `modulos/conexion/` (ajusta archivos como `config.php`).

5. **Configura el servidor web**:
   - Apunta el directorio raíz del servidor a la carpeta del proyecto.
   - Asegúrate de que `mod_rewrite` esté habilitado si usas Apache.

6. **Ejecuta las pruebas** (opcional):
   ```bash
   ./vendor/bin/phpunit
   ```

7. **Accede al sistema**:
   - Abre `http://tu-dominio/index.php` en un navegador.
   - Inicia sesión con credenciales predeterminadas (consulta la documentación interna o configura usuarios iniciales).

## Uso

- **Inicio de Sesión**: Accede con tu rol asignado (Admin, Usuario o Docente).
- **Navegación**: Usa el menú lateral para acceder a módulos como Inscripción, Boletines o Estadísticas.
- **Inscripción**: Completa formularios para registrar estudiantes y asignar cursos.
- **Reportes**: Genera PDFs desde los módulos de constancias o estadísticas.
- **Automatización**: Ejecuta scripts desde `modulos_nuevos/automatiza.php` para procesos anuales.

Para guías detalladas, consulta los manuales en `modulos/manual_tec.php` (técnico) y `modulos/manual_ua.php` (usuario).

## Contribución

Si deseas contribuir:
1. Haz un fork del repositorio.
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza commits descriptivos.
4. Envía un pull request.

Asegúrate de seguir las mejores prácticas de PHP y JavaScript, y ejecuta las pruebas antes de enviar cambios.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo `LICENSE` para más detalles (si existe).

## Contacto

Para soporte o preguntas, contacta al desarrollador principal o abre un issue en el repositorio.

---

*Desarrollado para optimizar la gestión educativa en Venezuela.*
