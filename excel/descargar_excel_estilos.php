<?php
// Asegúrate de que esta ruta sea correcta para tu proyecto
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Incluye el archivo que genera el array $registros
include_once './src/Application/informeGestion.php'; 

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

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

$sheet->fromArray($header, NULL, 'A1');

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

$sheet->fromArray($dataForExcel, NULL, 'A2');

// Estilos para el encabezado
$lastHeaderColumn = Coordinate::stringFromColumnIndex(count($header));
$headerRange = 'A1:' . $lastHeaderColumn . '1';
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FF337AB7', 
        ],
    ],
]);

// Obtener el rango completo de la tabla
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$dataRange = 'A1:' . $highestColumn . $highestRow;

// Añadir bordes a toda la tabla
$sheet->getStyle($dataRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
]);

// Autoajustar el ancho de las columnas
foreach (range('A', $highestColumn) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$fileName = 'informe_gestion_' . date('Y-m-d') . '.xlsx';
header('Content-Type: Application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Limpia el búfer de salida para eliminar cualquier contenido accidental
if (ob_get_length() > 0) {
    ob_clean();
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();