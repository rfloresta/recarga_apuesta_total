<?php

namespace App\Core\Entities;

class Recarga {
    private int $id;
    private float $monto;
    private string $fecha_hora;
    private string $foto_voucher;
    private int $cliente_id;
    private int $usuario_id;
    private int $banco_id;
    private int $canal_id;

    public function __construct() {
    }

    //aca van los getters y setters
    public function getId(): int {

        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getMonto(): float {
        return $this->monto;
    }

    public function setMonto($monto): void {
        $this->monto = $monto;
    }

    public function getFechaHora(): string {
        return $this->fecha_hora;
    }

    public function setFechaHora($fecha_hora): void {
        $this->fecha_hora = $fecha_hora;
    }

    public function getFotoVoucher(): string {
        return $this->foto_voucher;
    }

    public function setFotoVoucher($foto_voucher): void {
        $this->foto_voucher = $foto_voucher;
    }

    public function getClienteId(): int {
        return $this->cliente_id;
    }

    public function setClienteId($cliente_id): void {
        $this->cliente_id = $cliente_id;
    }

    public function getUsuarioId(): int {
        return $this->usuario_id;
    }

    public function setUsuarioId($usuario_id): void {
        $this->usuario_id = $usuario_id;
    }

    public function getBancoId(): int {
        return $this->banco_id;
    }

    public function setBancoId($banco_id): void {
        $this->banco_id = $banco_id;
    }

    public function getCanalId(): int {
        return $this->canal_id;
    }

    public function setCanalId($canal_id): void {
        $this->canal_id = $canal_id;
    }

    // Aca crea metodos extras para el manejo de la entidad

    public function getFechaFormateada(): string {
        return date('d/m/Y H:i', strtotime($this->fecha_hora));
    }

    public function getMontoFormateado(): string {
        return 'S/ ' . number_format($this->monto, 2);
    }

}