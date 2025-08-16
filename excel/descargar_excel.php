<?php
// Asegúrate de que esta ruta sea correcta para tu proyecto
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Incluye el archivo que genera el array $registros
// Asumimos que la lógica para crear el array $registros ya existe en este archivo
include_once './src/Application/informeGestion.php'; 
// Reemplaza con la ruta correcta si es necesario

// 1. Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 2. Definir las cabeceras de la tabla
$header = [
    'Proveedor',
    'Nombre Operario',
    'Bodega',
    'Nombre Producto',
    'Cantidad Asignada',
    'Cantidad Entregada',
    'Cantidad Faltante',
    'Merma',
    'Observaciones',
    'Fecha Revisión',
    'Cantidad Procesada'
];

// Escribir las cabeceras en la primera fila (A1, B1, C1, etc.)
$sheet->fromArray($header, NULL, 'A1');

// 3. Preparar los datos del array para escribirlos en el Excel
// Usamos un array temporal para organizar los datos de manera plana
$dataForExcel = [];
foreach ($registros as $registro) {
    $rowData = [
        $registro['proveedor'],
        $registro['nombreOperario'],
        $registro['bodega'],
        $registro['nombreProducto'],
        $registro['cantidadAsignada'],
        $registro['cantidadEntregada'],
        $registro['cantidadFaltante'],
        $registro['merma'],
        $registro['observaciones'],
        $registro['fechaRevision'],
        $registro['cantidadProcesada']
    ];
    $dataForExcel[] = $rowData;
}

// 4. Escribir los datos en la hoja de cálculo, empezando en la segunda fila (A2)
$sheet->fromArray($dataForExcel, NULL, 'A2');

// 5. Configurar los encabezados de la respuesta HTTP para la descarga
$fileName = 'informe_gestion_' . date('Y-m-d') . '.xlsx';
header('Content-Type: Application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// 6. Guardar el archivo en la salida para la descarga
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Terminar el script
exit();
