<?php

namespace App\Repositories\AdditionalService;

use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Models\AdditionalService;

class AdditionalServiceRepository implements AdditionalServiceRepositoryInterface
{
    private $additionalServiceModel;

    public function __construct(AdditionalService $additionalServiceModel)
    {
        $this->additionalServiceModel = $additionalServiceModel;
    }

    public function get(int $id): AdditionalService
    {
        return $this->additionalServiceModel->findOrFail($id);
    }

    public function allPaginated(int $limit)
    {
        return $this->additionalServiceModel
            ->with(['vat'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function create(array $data)
    {
        return $this->additionalServiceModel->create($data);
    }

    public function update(array $postData, int $id): void
    {
        $additional_service = $this->additionalServiceModel->findOrFail($id);

        $additional_service->name = $postData['name'] ?? $additional_service->name;
        $additional_service->net_price = $postData['net_price'] ?? $additional_service->net_price;
        $additional_service->gross_price = $postData['gross_price'] ?? $additional_service->gross_price;
        $additional_service->set_price_in_visit = $postData['set_price_in_visit'];
        $additional_service->nightly_net_price = $postData['nightly_net_price'] ?? $additional_service->nightly_net_price;
        $additional_service->nightly_gross_price = $postData['nightly_gross_price'] ?? $additional_service->nightly_gross_price;
        $additional_service->vat_id  = $postData['vat_id '] ?? $additional_service->vat_id;
        $additional_service->active = $postData['active'] ?? $additional_service->active;
        $additional_service->description = $postData['description'] ?? $additional_service->description;
        $additional_service->update();
    }

    public function delete(int $id)
    {
        $this->additionalServiceModel->destroy($id);
    }

    public function change_status(int $id): void
    {
        $additionalservice = $this->additionalServiceModel->findOrFail($id);

        $additionalservice->active = !$additionalservice->active;
        $additionalservice->save();
    }

    public function change_status_drive_to_customer(int $id): void
    {
        $additionalservice = $this->additionalServiceModel->findOrFail($id);

        $additionalservice->drive_to_customer = !$additionalservice->drive_to_customer;
        $additionalservice->save();
    }
}
