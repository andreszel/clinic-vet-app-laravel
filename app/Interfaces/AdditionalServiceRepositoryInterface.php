<?php

namespace App\Interfaces;

use App\Models\AdditionalService;

interface AdditionalServiceRepositoryInterface
{
    public const LIMIT_DEFAULT = 15;

    public function get(int $id): AdditionalService;
    public function allPaginated(int $limit);
    public function delete(int $id);
    public function create(array $data);
    public function update(array $postData, int $id): void;
    public function change_status(int $id): void;
}
