<?php

namespace App\Application;

use App\Infrastructure\Repository\InformeGestionExcel;
use App\Domain\InformeGestion;

include_once "../src/Domain/informeGestion.php";

class GenerarInformeGestion
{
    function run($datosSimulacion)
    {
        $registros = [];

        foreach ($datosSimulacion as $datos) {
            $informeGestion = new InformeGestion(
                $datos['proveedor'],
                $datos['nombreOperario'],
                $datos['bodega'],
                $datos['nombreProducto'],
                $datos['cantidadAsignada'],
                $datos['cantidadEntregada'],
                $datos['cantidadFaltante'],
                $datos['merma'],
                $datos['observaciones'],
                $datos['fechaRevision'],
                $datos['cantidadProcesada'],
                $datos['responsable']
            );
            $registro = [
                'proveedor' => $informeGestion->getProveedor(),
                'nombreOperario' => $informeGestion->getNombreOperario(),
                'bodega' => $informeGestion->getBodega(),
                'nombreProducto' => $informeGestion->getNombreProducto(),
                'cantidadAsignada' => $informeGestion->getCantidadAsignada(),
                'cantidadEntregada' => $informeGestion->getCantidadEntregada(),
                'cantidadFaltante' => $informeGestion->getCantidadFaltante(),
                'merma' => $informeGestion->getMerma(),
                'observaciones' => $informeGestion->getObservaciones(),
                'fechaRevision' => $informeGestion->getFechaRevision(),
                'cantidadProcesada' => $informeGestion->getCantidadProcesada(),
                'responsable' => $datos['responsable']
            ];
            array_push($registros, $registro);
        }
        return $registros;
    }
}
