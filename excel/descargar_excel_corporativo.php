<?php
require '../vendor/autoload.php';

use App\Infrastructure\DescargarExcelCorporativo;
use App\Application\GenerarInformeGestion;
use App\Infrastructure\Repository\InformeGestionExcelRepository;

// Ruta al archivo de configuración.
$configPath = './config.php';

$informeGestionExcelRepository = new InformeGestionExcelRepository();
$datosSimulacion = $informeGestionExcelRepository->getData();
$generarInformeGestion = new GenerarInformeGestion();
$registros = $generarInformeGestion->run($datosSimulacion);
$excelGenerator = new DescargarExcelCorporativo($configPath, $registros);
$excelGenerator->descargar();