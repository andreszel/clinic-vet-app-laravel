<?php

namespace App\Interfaces;

use App\Models\Medical;

interface MedicalRepositoryInterface
{
    public const LIMIT_DEFAULT = 15;

    public function get(int $id): Medical;
    public function allPaginated(int $limit);
    public function delete(int $id);
    public function create(array $data);
    public function update(array $postData, int $id): void;
    public function filterBy(?string $phrase, int $limit = self::LIMIT_DEFAULT);
}
