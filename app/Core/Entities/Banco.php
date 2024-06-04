<?php

namespace App\Core\Entities;

class Banco {
    private int $id;
    private string $codigo;
    private string $descripcion;
    private string $estado;

    public function __construct() {
    }

    //aca van los getters y setters
    public function getId(): int {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getCodigo(): string {
        return $this->codigo;
    }

    public function setCodigo($codigo): void {
        $this->codigo = $codigo;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function setEstado($estado): void {
        $this->estado = $estado;
    }
}