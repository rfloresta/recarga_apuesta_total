<?php

namespace App\Core\Entities;

class Cliente {
    private int $id;
    private string $player_id;
    private string $nombres;
    private string $apellidos;
    private float $saldo;

    public function __construct() {
    }

    //aca crea todos los getters y setters
    public function getId(): int {
        return $this->id;
    }
    
    public function setId($id): void {
        $this->id = $id;
    }

    public function getPlayerId(): string {
        return $this->player_id;
    }

    public function setPlayerId($player_id): void {
        $this->player_id = $player_id;
    }

    public function getNombres(): string {
        return $this->nombres;
    }

    public function setNombres($nombres): void {
        $this->nombres = $nombres;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function setApellidos($apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function getSaldo(): float {
        return $this->saldo;
    }

    public function setSaldo($saldo): void {
        $this->saldo = $saldo;
    }

    // Aca crea metodos extras para el manejo de la entidad

    public function getNombreCompleto(): string {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getSaldoFormateado(): string {
        return 'S/ ' . number_format($this->saldo, 2);
    }

}