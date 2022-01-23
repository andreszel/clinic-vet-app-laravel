<?php

namespace App\Repositories\Customer;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    private $customerModel;

    public function __construct(Customer $customerModel)
    {
        $this->customerModel = $customerModel;
    }

    public function get(int $id): Customer
    {
        return $this->customerModel->findOrFail($id);
    }

    public function allPaginated(int $limit)
    {
        return $this->customerModel
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function delete(int $id)
    {
        $this->customerModel->destroy($id);
    }

    public function create(array $data)
    {
        return $this->customerModel->create($data);
    }

    public function update(array $postData, int $id): void
    {
        $customer = $this->customerModel->findOrFail($id);

        $customer->name = $postData['name'] ?? $customer->name;
        $customer->number_herd = $postData['number_herd'] ?? $customer->number_herd;
        $customer->nip = $postData['nip'] ?? $customer->nip;
        $customer->surname = $postData['surname'] ?? $customer->surname;
        $customer->address = $postData['address'] ?? $customer->address;
        $customer->phone = $postData['phone'] ?? $customer->phone;
        $customer->email = $postData['email'] ?? $customer->email;
        $customer->update();
    }

    public function filterBy(?string $name, ?string $surname, int $limit = self::LIMIT_DEFAULT)
    {
        $query = $this->customerModel
            ->orderBy('created_at');

        if ($name) {
            $query->whereRaw('name like ?', ["$name%"]);
        }

        if ($surname) {
            $query->whereRaw('surname like ?', ["$surname%"]);
        }
        //dd($query->toSql());
        return $query->paginate($limit);
    }
}
