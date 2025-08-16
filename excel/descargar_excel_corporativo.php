<?php
// descargar_informe.php
// Script principal para la generación y descarga del archivo de Excel.
// Es flexible y no necesita ser modificado para cambiar los campos del reporte.

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Carga la configuración desde el archivo config.php.
$config = require './config.php';

// Asegúrate de que este archivo exista y genere un array de datos llamado $registros.
include_once '../src/application/informeGestionExcel.php'; 

// Crea un nuevo objeto de hoja de cálculo.
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle($config['empresa']['hoja_estilo']['nombre_hoja']);

// --- 1. Genera la tabla de datos de forma dinámica ---
$dataStartRow = 5;

// Obtiene los encabezados de las columnas del array de configuración.
$header = array_column($config['campos_datos_tabla'], 'header_text');
$sheet->fromArray($header, NULL, 'A' . $dataStartRow);

$dataForExcel = [];
// El ciclo ahora es flexible y lee la configuración.
foreach ($registros as $registro) {
    $rowData = [];
    foreach ($config['campos_datos_tabla'] as $campo) {
        // Accede al dato usando la clave del array de datos definida en la configuración.
        $rowData[] = $registro[$campo['data_key']];
    }
    $dataForExcel[] = $rowData;
}
$sheet->fromArray($dataForExcel, NULL, 'A' . ($dataStartRow + 1));

$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();

// --- 2. Añade el encabezado corporativo ---
$logoPath = $config['empresa']['logo_path'];
if (file_exists($logoPath)) {
    $drawing = new Drawing();
    $drawing->setName('Logo Corporativo');
    $drawing->setPath($logoPath);
    $drawing->setHeight($config['dimensiones']['logo_alto']);
    $drawing->setCoordinates('A1');
    $drawing->setWorksheet($sheet);
}

// Título y subtítulo del informe.
$sheet->setCellValue('B1', $config['empresa']['nombre']);
$sheet->getStyle('B1')->applyFromArray($config['estilos']['titulo']);
$sheet->mergeCells('B1:' . $highestColumn . '1');

$sheet->setCellValue('B2', $config['empresa']['subtitulo_informe']);
$sheet->getStyle('B2')->applyFromArray($config['estilos']['subtitulo']);
$sheet->mergeCells('B2:' . $highestColumn . '2');

// Ajusta el alto de las filas del encabezado.
$sheet->getRowDimension(1)->setRowHeight($config['dimensiones']['fila_titulo_alto']);
$sheet->getRowDimension(2)->setRowHeight($config['dimensiones']['fila_subtitulo_alto']);

// --- 3. Aplica estilos a la tabla de datos ---
$headerRange = 'A' . $dataStartRow . ':' . $highestColumn . $dataStartRow;
$dataRange = 'A' . $dataStartRow . ':' . $highestColumn . $highestRow;

$sheet->getStyle($headerRange)->applyFromArray([
    'font' => $config['estilos']['header_font'],
    'fill' => $config['estilos']['header_fill'],
    'borders' => $config['estilos']['table_borders'],
]);

// Aplica el color de fondo para las filas alternas.
$row = $dataStartRow + 1;
while ($row <= $highestRow) {
    if ($row % 2 !== 0) {
        $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
            'fill' => $config['estilos']['data_row_fill'],
        ]);
    }
    $row++;
}

// Aplica bordes y alineación a toda la tabla de datos.
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => $config['estilos']['table_borders'],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
]);

// --- 4. Configura el ancho de las columnas ---
if ($config['dimensiones']['auto_ajuste']) {
    foreach (range('A', $highestColumn) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
} else {
    foreach (range('A', $highestColumn) as $columnID) {
        if (isset($config['dimensiones']['ancho_columnas']['fijos'][$columnID])) {
            $sheet->getColumnDimension($columnID)->setWidth($config['dimensiones']['ancho_columnas']['fijos'][$columnID]);
        } else {
            $sheet->getColumnDimension($columnID)->setWidth($config['dimensiones']['ancho_columnas']['por_defecto']);
        }
    }
}

// Establece el pie de página del informe.
$sheet->getHeaderFooter()->setOddFooter($config['pie_de_pagina']['texto']);

// --- 5. Descarga el archivo ---
$fileName = 'informe_gestion_' . date('Y-m-d') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

if (ob_get_length() > 0) {
    ob_clean();
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
