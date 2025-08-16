<?php
namespace App\Domain;

class InformeGestion
{
    private $proveedor;
    private $nombreOperario;
    private $bodega;
    private $nombreProducto;
    private $cantidadAsignada;
    private $cantidadEntregada;
    private $cantidadFaltante;
    private $merma;
    private $observaciones;
    private $fechaRevision;
    private $cantidadProcesada;

    public function __construct(
        string $proveedor,
        string $nombreOperario,
        string $bodega,
        string $nombreProducto,
        int $cantidadAsignada,
        int $cantidadEntregada,
        int $cantidadFaltante,
        int $merma,
        string $observaciones,
        string $fechaRevision,
        int $cantidadProcesada
    ) {
        $this->proveedor = $proveedor;
        $this->nombreOperario = $nombreOperario;
        $this->bodega = $bodega;
        $this->nombreProducto = $nombreProducto;
        $this->cantidadAsignada = $cantidadAsignada;
        $this->cantidadEntregada = $cantidadEntregada;
        $this->cantidadFaltante = $cantidadFaltante;
        $this->merma = $merma;
        $this->observaciones = $observaciones;
        $this->fechaRevision = $fechaRevision;
        $this->cantidadProcesada = $cantidadProcesada;
    }
    
    // Getters
    public function getProveedor(): string
    {
        return $this->proveedor;
    }

    public function getNombreOperario(): string
    {
        return $this->nombreOperario;
    }

    public function getBodega(): string
    {
        return $this->bodega;
    }

    public function getNombreProducto(): string
    {
        return $this->nombreProducto;
    }

    public function getCantidadAsignada(): int
    {
        return $this->cantidadAsignada;
    }

    public function getCantidadEntregada(): int
    {
        return $this->cantidadEntregada;
    }

    public function getCantidadFaltante(): int
    {
        return $this->cantidadFaltante;
    }

    public function getMerma(): int
    {
        return $this->merma;
    }

    public function getObservaciones(): string
    {
        return $this->observaciones;
    }

    public function getFechaRevision(): string
    {
        return $this->fechaRevision;
    }

    public function getCantidadProcesada(): int
    {
        return $this->cantidadProcesada;
    }
}
