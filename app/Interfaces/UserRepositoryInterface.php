<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public const LIMIT_DEFAULT = 15;
    public const COMMISSION_SERVIES = 0;
    public const COMMISSION_MEDICALS = 50;
    public const TYPE_USER_ADMIN = 1;
    public const TYPE_USER_DOCTOR = 2;

    public function get(int $id): User;
    public function all(): Collection;
    public function allPaginated(int $limit);
    public function delete(int $userId);
    public function create(array $data);
    public function update(array $postData, int $userId): void;
    public function filterBy(?string $phrase, ?string $email, ?string $phone, int $type_id, int $limit = self::LIMIT_DEFAULT);
}
