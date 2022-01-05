<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;

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

        $users = $this->userRepository->all();

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
                'counter_visits' => 1,
                'medical_stats' => $medical_stats,
                'additional_service_stats' => $additional_service_stats
            ]
        );
    }

    private function addToMedicalStats($data, $arr)
    {
        //dd($data);
        $newData = [];
        $newData['medical_id'] = $data->medical_id;
        $newData['name'] = $data->medical->name;
        $newData['unit_measure_name'] = $data->medical->unit_measure->name;
        $newData['net_price'] = number_format($data->quantity * $data->net_price, 2, '.', ' ');
        $newData['gross_price'] = number_format($data->quantity * $data->gross_price, 2, '.', ' ');
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['medical_id'] == $data->medical_id) {
                $exists = true;

                $net_price = number_format($arr[$key]['net_price'] + ($data->quantity * $data->net_price), 2, '.', ' ');
                $gross_price = number_format($arr[$key]['gross_price'] + ($data->quantity * $data->gross_price), 2, '.', ' ');
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
        $newData['net_price'] = number_format($data->quantity * $data->net_price, 2);
        $newData['gross_price'] = number_format($data->quantity * $data->gross_price, 2);
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['additional_service_id'] == $data->additional_service_id) {
                $exists = true;

                $net_price = number_format($arr[$key]['net_price'] + ($data->quantity * $data->net_price), 2);
                $gross_price = number_format($arr[$key]['gross_price'] + ($data->quantity * $data->gross_price), 2);
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
