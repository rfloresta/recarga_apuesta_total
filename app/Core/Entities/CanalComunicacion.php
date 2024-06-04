<?php

namespace App\Core\Entities;

class CanalComunicacion {
    private int $id;
    private string $nombre;
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

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre($nombre): void {
        $this->nombre = $nombre;
    }

    
}