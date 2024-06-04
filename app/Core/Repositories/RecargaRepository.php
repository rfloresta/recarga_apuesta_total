<?php

namespace App\Core\Repositories;

use App\Core\Entities\Recarga;

interface RecargaRepository {

    public function save(Recarga $recarga): int;
    public function update(Recarga $recarga): int;
    public function find(): Recarga;
    public function all(): array;
    public function delete(int $id): bool;

    /**
     * Devuelve todas las recargas por player_id.
     * 
     * @param string $player_id
     * @return Recarga[] Un array de recargas filtradas por player_id.
     */
    public function findByPlayerID(int $player_id): array;

}
