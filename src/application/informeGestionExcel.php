<?php
include_once "../src/domain/informeGestion.php";

$registros = [];
$datosSimulacion = [
    [
        'proveedor' => 'Proveedor A',
        'nombreOperario' => 'Juan Pérez',
        'bodega' => 'Bodega Principal',
        'nombreProducto' => 'Producto X',
        'cantidadAsignada' => 100,
        'cantidadEntregada' => 95,
        'cantidadFaltante' => 5,
        'merma' => 2,
        'observaciones' => 'Observaciones de prueba 1',
        'fechaRevision' => '2025-08-15',
        'cantidadProcesada' => 93,
        'responsable' => "yomar"
    ],
    [
        'proveedor' => 'Proveedor B',
        'nombreOperario' => 'Ana Gómez',
        'bodega' => 'Bodega Norte',
        'nombreProducto' => 'Producto Y',
        'cantidadAsignada' => 250,
        'cantidadEntregada' => 248,
        'cantidadFaltante' => 2,
        'merma' => 1,
        'observaciones' => 'Ligeras fallas en el empaque.',
        'fechaRevision' => '2025-08-14',
        'cantidadProcesada' => 247,
        'responsable' => "yomar"
    ],
    [
        'proveedor' => 'Proveedor C',
        'nombreOperario' => 'Carlos Ramírez',
        'bodega' => 'Bodega Sur',
        'nombreProducto' => 'Producto Z',
        'cantidadAsignada' => 50,
        'cantidadEntregada' => 50,
        'cantidadFaltante' => 0,
        'merma' => 0,
        'observaciones' => 'Entrega sin novedades.',
        'fechaRevision' => '2025-08-13',
        'cantidadProcesada' => 50,
        'responsable' => "yomar"
    ]
];

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
