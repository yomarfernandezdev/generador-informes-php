<?php
namespace App\Infrastructure;
// DescargarExcelCorporativo.php
// Clase para refactorizar la generación y descarga de archivos Excel corporativos.

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Clase para generar y descargar informes de Excel de manera corporativa.
 *
 * Utiliza la biblioteca PhpSpreadsheet para crear un archivo XLSX con un
 * formato predefinido, incluyendo encabezado corporativo, tabla de datos
 * dinámica y estilos personalizados, basados en un archivo de configuración.
 *
 * @package App\Classes
 */
class DescargarExcelCorporativo
{
    /** @var array La configuración del informe, cargada desde un archivo. */
    private array $config;

    /** @var Spreadsheet El objeto principal de la hoja de cálculo. */
    private Spreadsheet $spreadsheet;

    /** @var int La fila inicial de la tabla de datos. */
    private int $dataStartRow = 5;

    /**
     * Constructor de la clase.
     *
     * @param string $configPath La ruta al archivo de configuración.
     * @param array $registros Los datos a exportar a Excel.
     */
    public function __construct(string $configPath, array $registros)
    {
        // Carga la configuración desde el archivo.
        $this->config = require $configPath;
        // Inicializa un nuevo objeto de hoja de cálculo.
        $this->spreadsheet = new Spreadsheet();
        // Genera la hoja de cálculo con los datos y estilos.
        $this->generarHojaCalculo($registros);
    }

    /**
     * Genera la hoja de cálculo completa con todos los componentes.
     *
     * Este método es la orquestación principal que llama a los demás
     * métodos para construir el archivo de Excel.
     *
     * @param array $registros Los datos a incluir en el informe.
     * @return void
     */
    private function generarHojaCalculo(array $registros): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle($this->config['empresa']['hoja_estilo']['nombre_hoja']);

        // Llamadas a métodos con responsabilidad única.
        $this->añadirEncabezadoCorporativo($sheet);
        $this->generarTablaDeDatos($sheet, $registros);
        $this->aplicarEstilosTabla($sheet);
        $this->ajustarAnchoColumnas($sheet);
        $this->añadirPieDePagina($sheet);
    }

    /**
     * Añade el encabezado corporativo al documento.
     *
     * Incluye el logo, el título y el subtítulo del informe.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo activa.
     * @return void
     */
    private function añadirEncabezadoCorporativo($sheet): void
    {
        // Añade el logo si la ruta es válida.
        $logoPath = $this->config['empresa']['logo_path'];
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo Corporativo');
            $drawing->setPath($logoPath);
            $drawing->setHeight($this->config['dimensiones']['logo_alto']);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // Título del informe.
        $sheet->setCellValue('B1', $this->config['empresa']['nombre']);
        $sheet->getStyle('B1')->applyFromArray($this->config['estilos']['titulo']);
        $sheet->mergeCells('B1:' . $sheet->getHighestColumn() . '1');

        // Subtítulo del informe.
        $sheet->setCellValue('B2', $this->config['empresa']['subtitulo_informe']);
        $sheet->getStyle('B2')->applyFromArray($this->config['estilos']['subtitulo']);
        $sheet->mergeCells('B2:' . $sheet->getHighestColumn() . '2');

        // Ajusta la altura de las filas.
        $sheet->getRowDimension(1)->setRowHeight($this->config['dimensiones']['fila_titulo_alto']);
        $sheet->getRowDimension(2)->setRowHeight($this->config['dimensiones']['fila_subtitulo_alto']);
    }

    /**
     * Genera la tabla de datos a partir del array de registros.
     *
     * Coloca los encabezados y los datos en la hoja de cálculo de forma dinámica.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo activa.
     * @param array $registros Los datos del informe.
     * @return void
     */
    private function generarTablaDeDatos($sheet, array $registros): void
    {
        // Obtiene los encabezados de las columnas del archivo de configuración.
        $header = array_column($this->config['campos_datos_tabla'], 'header_text');
        $sheet->fromArray($header, NULL, 'A' . $this->dataStartRow);

        // Prepara los datos para ser insertados en Excel.
        $dataForExcel = [];
        foreach ($registros as $registro) {
            $rowData = [];
            foreach ($this->config['campos_datos_tabla'] as $campo) {
                $rowData[] = $registro[$campo['data_key']];
            }
            $dataForExcel[] = $rowData;
        }

        // Inserta los datos en la hoja de cálculo.
        $sheet->fromArray($dataForExcel, NULL, 'A' . ($this->dataStartRow + 1));
    }

    /**
     * Aplica los estilos de formato a la tabla de datos.
     *
     * Incluye bordes, color de fondo para filas alternas y alineación.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo activa.
     * @return void
     */
    private function aplicarEstilosTabla($sheet): void
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();
        $headerRange = 'A' . $this->dataStartRow . ':' . $highestColumn . $this->dataStartRow;
        $dataRange = 'A' . $this->dataStartRow . ':' . $highestColumn . $highestRow;

        // Estilos para el encabezado de la tabla.
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => $this->config['estilos']['header_font'],
            'fill' => $this->config['estilos']['header_fill'],
            'borders' => $this->config['estilos']['table_borders'],
        ]);

        // Aplica el color de fondo para las filas alternas.
        for ($row = $this->dataStartRow + 1; $row <= $highestRow; $row++) {
            if ($row % 2 !== 0) {
                $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                    'fill' => $this->config['estilos']['data_row_fill'],
                ]);
            }
        }

        // Aplica bordes y alineación a toda la tabla de datos.
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => $this->config['estilos']['table_borders'],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    /**
     * Configura el ancho de las columnas.
     *
     * Puede ser un ajuste automático o un ancho fijo definido en la configuración.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo activa.
     * @return void
     */
    private function ajustarAnchoColumnas($sheet): void
    {
        $highestColumn = $sheet->getHighestColumn();
        if ($this->config['dimensiones']['auto_ajuste']) {
            foreach (range('A', $highestColumn) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        } else {
            foreach (range('A', $highestColumn) as $columnID) {
                $ancho = $this->config['dimensiones']['ancho_columnas']['fijos'][$columnID] ?? $this->config['dimensiones']['ancho_columnas']['por_defecto'];
                $sheet->getColumnDimension($columnID)->setWidth($ancho);
            }
        }
    }

    /**
     * Añade el pie de página al documento.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet La hoja de cálculo activa.
     * @return void
     */
    private function añadirPieDePagina($sheet): void
    {
        $sheet->getHeaderFooter()->setOddFooter($this->config['pie_de_pagina']['texto']);
    }

    /**
     * Descarga el archivo de Excel al navegador.
     *
     * Configura las cabeceras HTTP necesarias y envía el contenido del archivo.
     *
     * @return void
     */
    public function descargar(): void
    {
        $fileName = $this->config['empresa']['nombre_archivo'] . '_' . date('Y-m-d') . '.xlsx';

        // Configura las cabeceras para la descarga.
        header('Content-Type: Application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        // Limpia el búfer de salida para evitar problemas.
        if (ob_get_length() > 0) {
            ob_clean();
        }

        // Crea el escritor y guarda el archivo.
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
