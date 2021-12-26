<?php

namespace App\Repositories\Visit;

use App\Interfaces\VisitRepositoryInterface;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class VisitRepository implements VisitRepositoryInterface
{
    private Visit $visitModel;

    public function __construct(Visit $visitModel)
    {
        $this->visitModel = $visitModel;
    }

    public function get(int $id): Visit
    {
        return $this->visitModel->findOrFail($id);
    }

    public function allPaginated(int $limit)
    {
        return $this->visitModel
            ->with(['user', 'customer', 'pay_type'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function delete(int $id)
    {
        $this->visitModel->destroy($id);
    }

    public function create(array $data)
    {
        return $this->visitModel->create($data);
    }

    public function update(array $postData, int $id): void
    {
        $visit = $this->visitModel->find($id);

        $visit->user_id = $postData['user_id'] ?? $visit->user_id;
        $visit->customer_id = $postData['customer_id'] ?? $visit->customer_id;
        $visit->visit_number = $postData['visit_number'] ?? $visit->visit_number;
        $visit->visit_date = $postData['visit_date'] ?? $visit->visit_date;
        $visit->net_price = $postData['net_price'] ?? $visit->net_price;
        $visit->gross_price = $postData['gross_price'] ?? $visit->gross_price;
        $visit->pay_type_id = $postData['pay_type_id'] ?? $visit->pay_type_id;
        $visit->visit_cleared = $postData['visit_cleared'] ?? $visit->visit_cleared;
        $visit->description = $postData['description'] ?? $visit->description;

        $visit->update();
    }

    public function filterBy(?string $phrase, int $limit = self::LIMIT_DEFAULT)
    {
        $query = $this->visitModel
            ->with(['user', 'customer', 'pay_type'])
            ->orderBy('created_at');

        if ($phrase) {
            $query->whereRaw('name like ?', ["$phrase%"]);
        }

        return $query->paginate($limit);
    }

    public function maxVisitNumber(int $customerId): int
    {
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();

        $max_visit = $this->visitModel
            ->where('customer_id', $customerId)
            ->where('visit_date', '>=', $start)
            ->where('visit_date', '<=', $end)
            ->max('visit_number');

        if (is_numeric($max_visit)) {
            return $max_visit + 1;
        } else {
            return 1;
        }
    }
}
