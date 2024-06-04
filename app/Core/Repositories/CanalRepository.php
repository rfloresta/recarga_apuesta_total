<?php

namespace App\Core\Repositories;

use App\Core\Entities\Canal;

interface CanalRepository {

    public function save(Canal $canal): int;
    public function update(Canal $canal): int;
    public function find(): Canal;
    public function all(): array;
    public function delete(int $id): bool;

}
