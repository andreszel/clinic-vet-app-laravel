<?php

namespace App\Interfaces;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public const LIMIT_DEFAULT = 15;

    public function get(int $id): Customer;
    public function allPaginated(int $limit);
    public function delete(int $id);
    public function create(array $data);
    public function update(array $postData, int $id): void;
    public function filterBy(?string $name, ?string $surname, int $limit = self::LIMIT_DEFAULT);
}
