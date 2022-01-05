<?php

namespace App\Repositories\Visit;

use App\Interfaces\VisitRepositoryInterface;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        $visit->confirm_visit = $postData['confirm_visit'] ?? $visit->confirm_visit;
        $visit->description = $postData['description'] ?? $visit->description;

        $visit->update();
    }

    public function filterBy(?string $phrase, int $limit = self::LIMIT_DEFAULT)
    {
        $user = Auth::user();

        if ($user->type_id == 1) {
            $query = $this->visitModel
                ->with(['user', 'customer', 'pay_type'])
                ->orderBy('created_at');
        } else {
            $query = $this->visitModel
                ->where('user_id', $user->id)
                ->with(['user', 'customer', 'pay_type'])
                ->orderBy('created_at');
        }

        if ($phrase) {
            $query->whereRaw('name like ?', ["$phrase%"]);
        }

        return $query->paginate($limit);
    }

    public function maxVisitNumber(int $customerId): int
    {
        $start = Carbon::now()->startOfMonth()->timezone('Europe/Warsaw')->toDateString();
        $end = Carbon::now()->endOfMonth()->timezone('Europe/Warsaw')->toDateString();

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

    public function turnoverCurrentMonth(): float
    {
        $turnover = 0;
        $start = Carbon::now()->startOfMonth()->timezone('Europe/Warsaw')->toDateString();
        $end = Carbon::now()->endOfMonth()->timezone('Europe/Warsaw')->toDateString();

        $query = Visit::with(['visit_medicals', 'additional_services'])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at');
        $visits = $query->get();

        foreach ($visits as $visit) {
            foreach ($visit->visit_medicals as $visit_medical) {
                $gross_price = sprintf('%.2f', $visit_medical->quantity * $visit_medical->gross_price);
                $turnover += $gross_price;
            }

            foreach ($visit->additional_services as $additional_service) {
                $gross_price = sprintf('%.2f', $additional_service->quantity * $additional_service->gross_price);
                $turnover += $gross_price;
            }
        }

        return number_format($turnover, 2, '.', '');
    }

    public function marginCurrentMonth(): float
    {
        // Jeżeli administrator to wszystkie wizyty, jeżeli lekarz to tylko wizyty tego lekarza
        $margin = 0;
        $medicals_margin_doctor_all = 0;
        $medicals_margin_company_all = 0;
        $additional_services_margin_doctor_all = 0;
        $additional_services_margin_company_all = 0;
        // dane zalogowanego usera
        $user = Auth::user();
        // pierwszy i ostatni dzień bieżącego miesiąca
        $start = Carbon::now()->startOfMonth()->timezone('Europe/Warsaw')->toDateString();
        $end = Carbon::now()->endOfMonth()->timezone('Europe/Warsaw')->toDateString();

        // pobieramy wizyty
        $query = Visit::with(['user', 'visit_medicals', 'additional_services'])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end);
        if ($user->type_id != 1) {
            $query = $query->where('user_id', $user->id);
        }
        $query->orderBy('created_at');
        $visits = $query->get();

        foreach ($visits as $visit) {
            // wysokość marży za usługi i za leki dla lekarza, który dodał wizytę
            $commission_medicals = $visit->user->commission_medicals;
            $commission_services = $visit->user->commission_services;

            // przeglądamy wszystkie dodane leki do wizyty
            foreach ($visit->visit_medicals as $visit_medical) {
                // cała marża
                $medical_gross_margin = sprintf('%.2f', $visit_medical->quantity * $visit_medical->gross_margin);
                // marża dla lekarza
                $medical_margin_doctor = sprintf('%.2f', ($medical_gross_margin * ($commission_medicals / 100)));
                // marża dla firmy
                $medical_margin_company = sprintf('%.2f', ($medical_gross_margin - $medical_margin_doctor));

                // suma całej marży z leków
                $medicals_margin_doctor_all += $medical_margin_doctor;
                $medicals_margin_company_all += $medical_margin_company;
            }

            // przeglądamy wszystkie dodane usługi do wizyty
            foreach ($visit->additional_services as $additional_service) {
                // marżą jest cena brutto
                $additional_service_gross_margin = sprintf('%.2f', $additional_service->quantity * $additional_service->gross_price);

                // czy to jest dojazd
                $drive_to_customer = $additional_service->additionalservice->drive_to_customer;
                if ($drive_to_customer) {
                    // marża dla lekarza
                    $additional_service_margin_doctor = sprintf('%.2f', 0);
                    // marża dla firmy
                    $additional_service_margin_company = sprintf('%.2f', $additional_service_gross_margin);
                } else {
                    // marża dla lekarza
                    $additional_service_margin_doctor = sprintf('%.2f', ($additional_service_gross_margin * ($commission_services / 100)));
                    // marża dla firmy
                    $additional_service_margin_company = sprintf('%.2f', ($additional_service_gross_margin - $additional_service_margin_doctor));
                }

                // suma całej marży z leków
                $additional_services_margin_doctor_all += $additional_service_margin_doctor;
                $additional_services_margin_company_all += $additional_service_margin_company;
            }
        }

        // zalogowany administrator
        if ($user->type_id == 1) {
            $margin = sprintf('%.2f', ($medicals_margin_company_all + $additional_services_margin_company_all));
        } else {
            $margin = sprintf('%.2f', ($medicals_margin_doctor_all + $additional_services_margin_doctor_all));
        }

        return sprintf('%.2f', $margin);
    }

    public function countVisitsCurrentMonth(): int
    {
        // dane zalogowanego usera
        $user = Auth::user();
        // pierwszy i ostatni dzień bieżącego miesiąca
        $start = Carbon::now()->startOfMonth()->timezone('Europe/Warsaw')->toDateString();
        $end = Carbon::now()->endOfMonth()->timezone('Europe/Warsaw')->toDateString();

        // pobieramy wizyty
        $query = Visit::with(['user', 'visit_medicals', 'additional_services'])
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end);
        if ($user->type_id != 1) {
            $query = $query->where('user_id', $user->id);
        }
        $query->orderBy('created_at');
        $count = $query->count();

        return $count;
    }

    /* public function canManageVisit(int $id): bool
    {
        $user = Auth::user();
        $visit = $this->visitModel
            ->with(['user'])
            ->where('id', $id)
            ->first();

        $canManage = false;

        if ($user->type_id == 1) {
            $canManage = true;
        } else {
            if ($visit->confirm_visit) {

                $startTime = Carbon::parse($visit->updated_at)->timezone('Europe/Warsaw');
                $finishTime = Carbon::now()->timezone('Europe/Warsaw');
                $totalDuration = $finishTime->diffInMinutes($startTime);
                if ($totalDuration < self::MAX_TIME_TO_EDIT) {
                    $canManage = true;
                }
            } else {
                $canManage = true;
            }
        }

        return $canManage;
    } */
}
