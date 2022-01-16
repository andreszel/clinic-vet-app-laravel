<?php

namespace App\Interfaces;

use App\Models\Visit;

interface VisitRepositoryInterface
{
    public const LIMIT_DEFAULT = 15;
    public const MAX_STEP = 4;
    public const MAX_TIME_TO_EDIT = 10;

    public function get(int $id): Visit;
    public function allPaginated(int $limit);
    public function delete(int $id);
    public function create(array $data);
    public function update(array $postData, int $id): void;
    public function filterBy(?int $user_id, ?string $from_date, ?string $to_date, ?string $customer_name, ?string $customer_surname, int $limit = self::LIMIT_DEFAULT);
    public function maxVisitNumber(int $customerId): int;
    public function turnoverCurrentMonth(): float;
    public function marginCurrentMonth(): float;
    public function countVisitsCurrentMonth(): int;
    public function calcVisitStats($visit): array;
    public function sumTurnoverMarginStats($data): array;
}
