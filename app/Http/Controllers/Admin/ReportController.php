<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $user_id = $request->get('user_id');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $customer_phone = $request->get('customer_phone');
        $customer_name = $request->get('customer_name');
        $customer_surname = $request->get('customer_surname');
        $customer_email = $request->get('customer_email');
        $paramsCount = 0;
        $medical_stats = [];
        $additional_service_stats = [];
        // Statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        $visit_stats = [];
        $sum_visit_stats = [];

        $users = $this->userRepository->all();

        // Wyszukiwarka
        $query = Visit::with(['user', 'customer', 'pay_type', 'visit_medicals', 'additional_services'])
            ->orderBy('created_at');

        if ($user_id) {
            $paramsCount++;
            $query->whereRaw('user_id = ?', [$user_id]);
        }

        if ($from_date) {
            $paramsCount++;
            $query->whereRaw('visit_date >= ?', [$from_date]);
        }

        if ($to_date) {
            $paramsCount++;
            $query->whereRaw('visit_date <= ?', [$to_date]);
        }

        if ($customer_phone) {
            $paramsCount++;
            $query->whereHas('customer', function ($query) use ($customer_phone) {
                $query->whereRaw('phone LIKE ?', ['%' . $customer_phone . '%']);
            });
        }

        if ($customer_name) {
            $paramsCount++;
            $query->whereHas('customer', function ($query) use ($customer_name) {
                $query->whereRaw('name LIKE ?', ['%' . $customer_name . '%']);
            });
        }

        if ($customer_surname) {
            $paramsCount++;
            $query->whereHas('customer', function ($query) use ($customer_surname) {
                $query->whereRaw('surname LIKE ?', ['%' . $customer_surname . '%']);
            });
        }

        if ($customer_email) {
            $paramsCount++;
            $query->whereHas('customer', function ($query) use ($customer_email) {
                $query->whereRaw('email LIKE ?', ['%' . $customer_email . '%']);
            });
        }

        //dd($query->toSql());
        if ($paramsCount > 0) {
            $visits = $query->get();
            //dd($visits);

            foreach ($visits as $visit) {
                foreach ($visit->visit_medicals as $visit_medical) {
                    $medical_stats = $this->addToMedicalStats($visit_medical, $medical_stats);
                }

                foreach ($visit->additional_services as $additional_service) {
                    $additional_service_stats = $this->addToAdditionalServiceStats($additional_service, $additional_service_stats);
                }
            }
        } else {
            $visits = Collection::make(new Visit);
        }

        // Statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        foreach ($users as $user) {
            $item = [];
            $item['name'] = $user->name;
            $item['surname'] = $user->surname;

            $stats = $this->visitStats($user->id);
            $item['stats'] = $stats;

            $visit_stats[] = $item;

            // sumujemy wszystkie wizyty dla wszystkich lekarzy z danego okresu
            $sum_visit_stats['last_year'] = (isset($sum_visit_stats['last_year']) ? (int)$sum_visit_stats['last_year'] + $stats['last_year'] : $stats['last_year']);
            $sum_visit_stats['last_six_months'] = (isset($sum_visit_stats['last_six_months']) ? (int)$sum_visit_stats['last_six_months'] + $stats['last_six_months'] : $stats['last_six_months']);
            $sum_visit_stats['last_three_months'] = (isset($sum_visit_stats['last_three_months']) ? (int)$sum_visit_stats['last_three_months'] + $stats['last_three_months'] : $stats['last_three_months']);
            $sum_visit_stats['last_month'] = (isset($sum_visit_stats['last_month']) ? (int)$sum_visit_stats['last_month'] + $stats['last_month'] : $stats['last_month']);
            $sum_visit_stats['last_week'] = (isset($sum_visit_stats['last_week']) ? (int)$sum_visit_stats['last_week'] + $stats['last_week'] : $stats['last_week']);
            $sum_visit_stats['today'] = (isset($sum_visit_stats['today']) ? (int)$sum_visit_stats['today'] + $stats['today'] : $stats['today']);
        }

        return view(
            'admin.reports.list',
            [
                'visits' => $visits,
                'users' => $users,
                'user_id' => $user_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'customer_phone' => $customer_phone,
                'customer_name' => $customer_name,
                'customer_surname' => $customer_surname,
                'customer_email' => $customer_email,
                'counter' => 1,
                'medical_stats' => $medical_stats,
                'additional_service_stats' => $additional_service_stats,
                'visit_stats' => $visit_stats,
                'sum_visit_stats' => $sum_visit_stats
            ]
        );
    }

    private function visitStats($user_id)
    {
        $stats = [];

        // 1 year
        $start_year = Carbon::today()->subMonth(12)->timezone('Europe/Warsaw')->toDateString();
        // 6 months
        $start_six_months = Carbon::today()->subMonth(6)->timezone('Europe/Warsaw')->toDateString();
        // 3 months
        $start_three_months = Carbon::today()->subMonth(3)->timezone('Europe/Warsaw')->toDateString();
        // 1 month
        $start_one_month = Carbon::today()->subMonth(1)->timezone('Europe/Warsaw')->toDateString();
        // 1 week
        $start_one_week = Carbon::today()->subDay(7)->timezone('Europe/Warsaw')->toDateString();
        // current day
        $end = Carbon::today()->timezone('Europe/Warsaw')->toDateString();



        $stats['last_year'] = $this->countVisits($user_id, $start_year, $end);
        $stats['last_six_months'] = $this->countVisits($user_id, $start_six_months, $end);
        $stats['last_three_months'] = $this->countVisits($user_id, $start_three_months, $end);
        $stats['last_month'] = $this->countVisits($user_id, $start_one_month, $end);
        $stats['last_week'] = $this->countVisits($user_id, $start_one_week, $end);
        $stats['today'] = $this->countVisits($user_id, $end, $end);

        return $stats;
    }

    private function countVisits($user_id, $start, $end)
    {
        $query = Visit::with(['user', 'customer', 'pay_type', 'visit_medicals', 'additional_services'])
            ->whereRaw('visit_date >= ?', [$start])
            ->whereRaw('visit_date <= ?', [$end])
            ->whereRaw('user_id = ?', [$user_id])
            ->orderBy('created_at');
        return $query->count();
    }

    private function addToMedicalStats($data, $arr)
    {
        //dd($data);
        $newData = [];
        $newData['medical_id'] = $data->medical_id;
        $newData['name'] = $data->medical->name;
        $newData['unit_measure_name'] = $data->medical->unit_measure->name;
        $newData['net_price'] = sprintf('%.2f', $data->quantity * $data->net_price);
        $newData['gross_price'] = sprintf('%.2f', $data->quantity * $data->gross_price);
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['medical_id'] == $data->medical_id) {
                $exists = true;

                $net_price = sprintf('%.2f', $arr[$key]['net_price'] + ($data->quantity * $data->net_price));
                $gross_price = sprintf('%.2f', $arr[$key]['gross_price'] + ($data->quantity * $data->gross_price));
                $quantity = $arr[$key]['quantity'] + $data->quantity;

                $arr[$key]['net_price'] = $net_price;
                $arr[$key]['gross_price'] = $gross_price;
                $arr[$key]['quantity'] = $quantity;
            }
        }

        if (!$exists)
            $arr[] = $newData;
        return $arr;
    }

    private function addToAdditionalServiceStats($data, $arr)
    {
        $newData = [];
        $newData['additional_service_id'] = $data->additional_service_id;
        $newData['name'] = $data->additionalservice->name;
        $newData['net_price'] = sprintf('%.2f', $data->quantity * $data->net_price);
        $newData['gross_price'] = sprintf('%.2f', $data->quantity * $data->gross_price);
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['additional_service_id'] == $data->additional_service_id) {
                $exists = true;

                $net_price = sprintf('%.2f', $arr[$key]['net_price'] + ($data->quantity * $data->net_price));
                $gross_price = sprintf('%.2f', $arr[$key]['gross_price'] + ($data->quantity * $data->gross_price));
                $quantity = $arr[$key]['quantity'] + $data->quantity;

                $arr[$key]['net_price'] = $net_price;
                $arr[$key]['gross_price'] = $gross_price;
                $arr[$key]['quantity'] = $quantity;
            }
        }

        if (!$exists)
            $arr[] = $newData;
        return $arr;
    }
}
