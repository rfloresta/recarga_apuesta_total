<?php

namespace App\Core\Repositories;

use App\Core\Entities\Cliente;

interface ClienteRepository {

    public function save(Banco $banco): int;
    public function update(Banco $banco): int;
    public function find(): Banco;
    public function all(): array;
    public function delete(int $id): bool;
}
