<?php

namespace App\Repositories\Medical;

use App\Interfaces\MedicalRepositoryInterface;
use App\Models\Medical;
use Illuminate\Support\Collection;

class MedicalRepository implements MedicalRepositoryInterface
{
    private Medical $medicalModel;

    public function __construct(Medical $medicalModel)
    {
        $this->medicalModel = $medicalModel;
    }

    public function get(int $id): Medical
    {
        return $this->medicalModel->findOrFail($id);
    }

    public function allPaginated(int $limit)
    {
        return $this->medicalModel
            ->with(['vat_buy', 'vat_sell', 'unit_measure'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function delete(int $id)
    {
        $this->medicalModel->destroy($id);
    }

    public function create(array $data)
    {
        return $this->medicalModel->create($data);
    }

    public function update(array $postData, int $id): void
    {
        $medical = $this->medicalModel->find($id);

        $medical->name = $postData['name'] ?? $medical->name;
        $medical->vat_buy_id = $postData['vat_buy_id'] ?? $medical->vat_buy_id;
        $medical->vat_sell_id = $postData['vat_sell_id'] ?? $medical->vat_sell_id;
        $medical->net_price_buy = $postData['net_price_buy'] ?? $medical->net_price_buy;
        $medical->gross_price_buy = $postData['gross_price_buy'] ?? $medical->gross_price_buy;
        $medical->net_price_sell = $postData['net_price_sell'] ?? $medical->net_price_sell;
        $medical->gross_price_sell = $postData['gross_price_sell'] ?? $medical->gross_price_sell;
        $medical->net_margin = $postData['net_margin'] ?? $medical->net_margin;
        $medical->gross_margin = $postData['gross_margin'] ?? $medical->gross_margin;
        $medical->unit_measure_id = $postData['unit_measure_id'] ?? $medical->unit_measure_id;
        $medical->active = $postData['active'] ?? $medical->active;

        $medical->update();
    }

    public function filterBy(?string $phrase, int $limit = self::LIMIT_DEFAULT)
    {
        $query = $this->medicalModel
            ->with(['vat_buy', 'vat_sell', 'unit_measure'])
            ->orderBy('created_at');

        if ($phrase) {
            $query->whereRaw('name like ?', ["$phrase%"]);
        }

        return $query->paginate($limit);
    }
}
