<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function get(int $userId): User
    {
        return $this->userModel->findOrFail($userId);
    }

    public function all(): Collection
    {
        return $this->userModel
            ->with(['type'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function allPaginated(int $limit)
    {
        return $this->userModel
            ->with(['type'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function delete(int $userId)
    {
        $this->userModel->destroy($userId);
    }

    public function create(array $data)
    {
        return $this->userModel->create($data);
    }

    public function update(array $postData, int $userId): void
    {
        $user = $this->userModel->find($userId);

        $user->name = $postData['name'] ?? $user->name;
        $user->surname = $postData['surname'] ?? $user->surname;
        $user->active = $postData['active'] ?? $user->active;
        $user->phone = $postData['phone'] ?? $user->phone;
        $user->type_id = $postData['type_id'] ?? $user->type_id;
        $user->commission_services = $postData['commission_services'] ?? $user->commission_services;
        $user->commission_medicals = $postData['commission_medicals'] ?? $user->commission_medicals;

        $user->update();
    }

    public function change_status(int $id): void
    {
        $user = $this->userModel->findOrFail($id);

        $user->active = !$user->active;
        $user->save();
    }

    public function filterBy(?string $phrase, ?string $email, ?string $phone, int $limit = self::LIMIT_DEFAULT)
    {
        $query = $this->userModel
            ->with(['type'])
            ->orderBy('created_at');

        if ($phrase) {
            $query->whereRaw('name like ?', ["$phrase%"]);
        }

        if ($email) {
            $query->whereRaw('email = ?', ["$email"]);
        }

        if ($phone) {
            $query->whereRaw('phone like ?', ["%$phone%"]);
        }

        return $query->paginate($limit);
    }
}
