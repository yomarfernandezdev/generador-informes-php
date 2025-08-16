# Generador de Informes Excel 📊

Este script PHP (`descargar_informe.php`) es una herramienta robusta y flexible diseñada para generar y descargar informes en formato Excel (XLSX). Su principal ventaja es que **no requiere modificaciones en su código principal** para cambiar los datos, encabezados o estilos del reporte. Toda la configuración se gestiona a través de un archivo externo, lo que permite una personalización rápida y sencilla.

## Características Principales ✨

  * **Configuración Externa**: La estructura, encabezados y estilos del reporte se definen en el archivo `config.php`, facilitando la adaptación a diferentes tipos de informes.
  * **Generación Dinámica de Tablas**: Construye la tabla de datos de forma automática a partir de un arreglo de datos (`$registros`) y la configuración de columnas.
  * **Diseño Profesional**: Incluye funcionalidades para agregar un logo corporativo, títulos, subtítulos y un pie de página.
  * **Estilización Avanzada**: Aplica estilos personalizados a los encabezados y a las filas de datos (incluyendo sombreado para filas alternas), y configura el ancho de las columnas.
  * **Manejo de Librerías Modernas**: Utiliza la librería **PhpSpreadsheet** para la manipulación de archivos Excel, garantizando compatibilidad y un rendimiento óptimo.

## Requisitos 🛠️

  * **PHP 7.4+**: El script está diseñado para funcionar en versiones modernas de PHP.
  * **Composer**: Para gestionar las dependencias del proyecto, específicamente la librería `PhpSpreadsheet`.

## Instalación 🚀

1.  **Clonar el repositorio** o descargar los archivos del proyecto.

2.  **Ejecutar Composer**: Desde la raíz del proyecto, ejecuta el siguiente comando para instalar `PhpSpreadsheet`:

    ```bash
    composer install
    ```

3.  **Configuración del Informe**:

      * Asegúrate de tener un archivo `config.php` en la misma carpeta que `descargar_informe.php`. Este archivo debe devolver un array con todas las configuraciones del informe.
      * Prepara tu fuente de datos. El script asume que un archivo (`../src/Application/informeGestionExcel.php` en el ejemplo) genera un array llamado `$registros`.

## Estructura de Archivos 📂

```
.
├── descargar_informe.php    # Script principal
├── config.php               # Archivo de configuración del informe
├── composer.json            # Archivo de dependencias de Composer
├── composer.lock
├── vendor/                  # Dependencias de Composer (incluyendo PhpSpreadsheet)
└── src/
    └── Application/
        └── informeGestionExcel.php  # Archivo que genera los datos ($registros)
```

## Uso 💻

Para generar y descargar el informe, simplemente accede al script `descargar_informe.php` desde tu navegador o a través de una llamada HTTP.

```
http://tu-servidor.com/ruta-al-proyecto/descargar_informe.php
```

El script se encargará automáticamente de generar el archivo Excel y enviarlo al navegador para su descarga.

## Personalización 🔧

Puedes modificar completamente la apariencia y los datos del informe editando los siguientes archivos:

  * **`config.php`**: Edita este archivo para cambiar los encabezados de la tabla, las claves de los datos, los títulos, el logo, los estilos de fuente, los colores y las dimensiones.
  * **`informeGestionExcel.php`**: Modifica este archivo para cambiar la lógica de obtención de datos y generar el array `$registros` que alimentará el informe. Esto te permite conectar el generador con cualquier base de datos, API u otra fuente de información.
