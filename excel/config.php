<?php
// config.php
// Este archivo centraliza la configuración para la generación de informes de Excel.
// La lógica principal ahora es flexible y se basa en este mapeo de datos.

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

return [
    // Sección para la información de la empresa en el encabezado.
    'empresa' => [
        'nombre' => 'Nombre de tu Empresa S.A.S',
        'subtitulo_informe' => 'Informe de Gestión de Bodega',
        'logo_path' => './recursos/logos/logo_corporativo.png',
        'hoja_estilo' => [
            'nombre_hoja' => 'Informe de Gestión',
        ]
    ],
    // Sección para configurar las dimensiones de filas y columnas.
    'dimensiones' => [
        'logo_alto' => 50,
        'fila_titulo_alto' => 40,
        'fila_subtitulo_alto' => 20,
        'auto_ajuste' => true,
        'ancho_columnas' => [
            'fijos' => [
                'A' => 20,
                'E' => 15,
                'F' => 15,
                'H' => 10,
            ],
            'por_defecto' => 20,
        ],
    ],
    // Sección para definir los estilos de fuente, relleno y bordes.
    'estilos' => [
        'titulo' => [
            'font' => ['bold' => true, 'size' => 20, 'color' => ['argb' => 'FF2A3644']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ],
        'subtitulo' => [
            'font' => ['bold' => false, 'size' => 14, 'color' => ['argb' => 'FF546A76']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ],
        'header_font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
            'size' => 11,
        ],
        'header_fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF19537E'],
        ],
        'data_row_fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFE8E8E8'],
        ],
        'table_borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFBDBDBD'],
            ],
        ],
    ],
    // ** La clave para la flexibilidad **
    // 'campos_datos_tabla' define el mapeo entre los encabezados de las
    // columnas del Excel y las claves del array de datos ($registro).
    'campos_datos_tabla' => [
        [
            'header_text' => 'Proveedor',
            'data_key' => 'proveedor'
        ],
        [
            'header_text' => 'Nombre Operario',
            'data_key' => 'nombreOperario'
        ],
        [
            'header_text' => 'Bodega',
            'data_key' => 'bodega'
        ],
        [
            'header_text' => 'Nombre Producto',
            'data_key' => 'nombreProducto'
        ],
        [
            'header_text' => 'Cantidad Asignada',
            'data_key' => 'cantidadAsignada'
        ],
        [
            'header_text' => 'Cantidad Entregada',
            'data_key' => 'cantidadEntregada'
        ],
        [
            'header_text' => 'Cantidad Faltante',
            'data_key' => 'cantidadFaltante'
        ],
        [
            'header_text' => 'Merma',
            'data_key' => 'merma'
        ],
        [
            'header_text' => 'Observaciones',
            'data_key' => 'observaciones'
        ],
        [
            'header_text' => 'Fecha Revisión',
            'data_key' => 'fechaRevision'
        ],
        [
            'header_text' => 'Cantidad Procesada',
            'data_key' => 'cantidadProcesada'
        ],
        [
            'header_text' => 'Responsable',
            'data_key' => 'responsable'
        ],
    ],
    // Configuración para el pie de página.
    'pie_de_pagina' => [
        'texto' => '&L&"Arial"&9Informe Generado el &D&"Arial"&9 &R&"Arial"&9Página &P de &N',
    ],
];
