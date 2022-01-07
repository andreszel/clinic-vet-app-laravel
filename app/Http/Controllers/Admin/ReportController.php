<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VisitRepositoryInterface;
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
    private VisitRepositoryInterface $visitRepository;

    public function __construct(UserRepositoryInterface $userRepository, VisitRepositoryInterface $visitRepository)
    {
        $this->userRepository = $userRepository;
        $this->visitRepository = $visitRepository;
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
        $medical_stats_vat_price_sum = 0;
        $medical_stats_net_price_sum = 0;
        $medical_stats_gross_price_sum = 0;
        $turnover_margin_stats = [];
        $turnover_margin_stats_sum = [];
        $additional_service_stats = [];
        $additional_service_stats_vat_price_sum = 0;
        $additional_service_stats_net_price_sum = 0;
        $additional_service_stats_gross_price_sum = 0;
        $services_medicals_stats_gross_price_sum = 0;
        // Statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        $visit_stats = [];
        $sum_visit_stats = [];
        $visit_stats_by_pay_type = [];
        $summary_visit_stats_by_pay_type = [];
        // Raport 4. Link do Szczegółowe rozliczenie wizyt wszystkich lekarzy
        $visit_calc_details = [];

        $url = $request->url();
        $uri = $request->getRequestUri();

        // get params from url
        /* $separator = '&';
        $uri_arr = explode('?', $uri);
        $params_arr = explode($separator, $uri_arr[1]);
        unset($params_arr[0]);
        $params_string = implode($separator, $params_arr);

        $urlWithQueryString = $request->fullUrl();
        $input_all = $request->all();
        $input_collect = $request->collect();

        dump($url);
        dump($uri);
        dump($uri_arr);
        dump($params_arr);
        dump($params_string);
        dump($urlWithQueryString);
        dump($input_all);
        dump($input_collect);
        dump($request);
        dd(); */

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
        $visits = $query->get();

        //dd($visits);

        // Raport 1: Statystyka wizyt wszystkich lekarzy - statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        foreach ($users as $user) {
            $item = [];
            // doctor name
            $item['name'] = $user->name;
            $item['surname'] = $user->surname;

            // get stats
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

        foreach ($visits as $visit) {
            foreach ($visit->visit_medicals as $visit_medical) {
                $vat_price = $visit_medical->sum_gross_price - $visit_medical->sum_net_price;

                // sum - Raport 2: Statystyka leków i usług dodatkowych - lista leków
                $medical_stats_vat_price_sum += $vat_price;
                $medical_stats_net_price_sum += $visit_medical->sum_net_price;
                $medical_stats_gross_price_sum += $visit_medical->sum_gross_price;

                // items - Raport 2: Statystyka leków i usług dodatkowych - lista leków
                $medical_stats = $this->addToMedicalStats($visit_medical, $medical_stats);
            }

            foreach ($visit->additional_services as $additional_service) {
                $vat_price = $additional_service->sum_gross_price - $additional_service->sum_net_price;

                // sum - Raport 2: Statystyka leków i usług dodatkowych - lista usług
                $additional_service_stats_vat_price_sum += $vat_price;
                $additional_service_stats_net_price_sum += $additional_service->sum_net_price;
                $additional_service_stats_gross_price_sum += $additional_service->sum_gross_price;

                // items - Raport 2: Statystyka leków i usług dodatkowych - lista usług
                $additional_service_stats = $this->addToAdditionalServiceStats($additional_service, $additional_service_stats);
            }

            // Raport 3: Statystyka obrotów i zysku
            $turnover_margin_stats = $this->addTurnoverMarginStats($visit, $turnover_margin_stats);
        }

        // Raport 2: Statystyka leków i usług dodatkowych
        $services_medicals_stats_gross_price_sum = $additional_service_stats_gross_price_sum + $medical_stats_gross_price_sum;

        // sum - Raport 3: Statystyka obrotów i zysku
        $turnover_margin_stats_sum = $this->visitRepository->sumTurnoverMarginStats($turnover_margin_stats);

        // sum - Raport 5. Rozliczenie wizyt
        foreach ($visits as $visit) {
            $visit_stats_by_pay_type = $this->getVisitStatsByPayType($visit, $visit_stats_by_pay_type);
            $summary_visit_stats_by_pay_type = $this->getSummaryVisitStatsByPayType($visit, $summary_visit_stats_by_pay_type);

            // Raport 4. Szczegółowe rozliczenie wizyt wszystkich lekarzy za okres od 2021-11-01 do 2021-11-30
            $visit_calc_details = $this->getVisitCalcDetails($visit, $visit_calc_details);
        }

        $visit_calc_details = $this->addSummaryVisitToCalcDetails($visit_calc_details);

        //dd($visit_calc_details);

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
                'medical_stats_vat_price_sum' => $medical_stats_vat_price_sum,
                'medical_stats_net_price_sum' => $medical_stats_net_price_sum,
                'medical_stats_gross_price_sum' => $medical_stats_gross_price_sum,
                'additional_service_stats' => $additional_service_stats,
                'additional_service_stats_vat_price_sum' => $additional_service_stats_vat_price_sum,
                'additional_service_stats_net_price_sum' => $additional_service_stats_net_price_sum,
                'additional_service_stats_gross_price_sum' => $additional_service_stats_gross_price_sum,
                'services_medicals_stats_gross_price_sum' => $services_medicals_stats_gross_price_sum,
                'turnover_margin_stats' => $turnover_margin_stats,
                'turnover_margin_stats_sum' => $turnover_margin_stats_sum,
                'visit_stats' => $visit_stats,
                'sum_visit_stats' => $sum_visit_stats,
                'visit_stats_by_pay_type' => $visit_stats_by_pay_type,
                'summary_visit_stats_by_pay_type' => $summary_visit_stats_by_pay_type,
                'count_summary_visit_stats_by_pay_type' => count($summary_visit_stats_by_pay_type),
                'visit_calc_details' => $visit_calc_details
            ]
        );
    }

    private function getVisitCalcDetails($visit, $arr)
    {
        $calcVisitStats = $this->visitRepository->calcVisitStats($visit);

        $newVisit = $visit->toArray();
        $newVisit['margin_company'] = $calcVisitStats['margin_company'];
        $newVisit['margin_doctor'] = $calcVisitStats['margin_doctor'];
        $newVisit['pay_type_name'] = $calcVisitStats['pay_type_name'];

        if (in_array($visit->user_id, $arr)) {
            $arr[$visit->user_id]['visits'][] = $newVisit;
        } else {
            $arr[$visit->user_id]['name'] = $visit->user->name;
            $arr[$visit->user_id]['surname'] = $visit->user->surname;
            $arr[$visit->user_id]['visits'][] = $newVisit;
        }

        return $arr;
    }

    public function addSummaryVisitToCalcDetails($arr)
    {
        foreach ($arr as $key => $item) {
            //dd($item);
            $sum_net_price_medical = 0;
            $sum_gross_price_medical = 0;
            $sum_vat_price_medical = 0;
            foreach ($item['visits'] as $key_visit => $visit) {
                foreach ($visit['visit_medicals'] as $key_medical => $visit_medical) {
                    $sum_net_price_medical += $visit_medical['sum_net_price'];
                    $sum_gross_price_medical += $visit_medical['sum_gross_price'];
                    $sum_vat_price_medical += $visit_medical['sum_gross_price'] - $visit_medical['sum_net_price'];
                }

                $arr[$key]['sum_net_price_medical'] = $sum_net_price_medical;
                $arr[$key]['sum_gross_price_medical'] = $sum_gross_price_medical;
                $arr[$key]['sum_vat_price_medical'] = $sum_vat_price_medical;

                $sum_net_price_additional_service = 0;
                $sum_gross_price_additional_service = 0;
                $sum_vat_price_additional_service = 0;
                foreach ($visit['additional_services'] as $key_additional_service => $additional_service) {
                    $sum_net_price_additional_service += $additional_service['sum_net_price'];
                    $sum_gross_price_additional_service += $additional_service['sum_gross_price'];
                    $sum_vat_price_additional_service += $additional_service['sum_gross_price'] - $additional_service['sum_net_price'];
                }

                $arr[$key]['sum_net_price_additional_service'] = $sum_net_price_medical;
                $arr[$key]['sum_gross_price_additional_service'] = $sum_gross_price_medical;
                $arr[$key]['sum_vat_price_additional_service'] = $sum_vat_price_medical;
            }
        }

        return $arr;
    }

    private function getVisitStatsByPayType($visit, $arr)
    {
        $newItem = [];

        $calcVisitStats = $this->visitRepository->calcVisitStats($visit);
        $newItem['visit_date'] = $visit->visit_date;
        $newItem['customer_name'] = $visit->customer->name;
        $newItem['customer_surname'] = $visit->customer->surname;
        $newItem['user_name'] = $visit->user->name;
        $newItem['user_surname'] = $visit->user->surname;
        $newItem['gross_price'] = $calcVisitStats['gross_price'];
        $newItem['margin_company'] = $calcVisitStats['margin_company'];
        $newItem['margin_doctor'] = $calcVisitStats['margin_doctor'];
        $newItem['pay_type_name'] = $visit->pay_type->name;

        $arr[] = $newItem;

        return $arr;
    }

    private function getSummaryVisitStatsByPayType($visit, $arr)
    {
        $newItem = [];

        $calcVisitStats = $this->visitRepository->calcVisitStats($visit);
        $exists = false;

        foreach ($arr as $key => $item) {
            if ($item['pay_type_id'] == $calcVisitStats['pay_type_id']) {
                //modyfikujemy dane
                $newItem = $item;

                $newItem['net_price'] += $calcVisitStats['net_price'];
                $newItem['gross_price'] += $calcVisitStats['gross_price'];
                $newItem['margin_company'] += $calcVisitStats['margin_company'];
                $newItem['margin_doctor'] += $calcVisitStats['margin_doctor'];

                // nadpisujemy całą tablicę
                $arr[$key] = $newItem;

                return $arr;
            }
        }
        if (!$exists) {
            $newItem['pay_type_id'] = $calcVisitStats['pay_type_id'];
            $newItem['pay_type_name'] = $calcVisitStats['pay_type_name'];
            $newItem['net_price'] = $calcVisitStats['net_price'];
            $newItem['gross_price'] = $calcVisitStats['gross_price'];
            $newItem['margin_company'] = $calcVisitStats['margin_company'];
            $newItem['margin_doctor'] = $calcVisitStats['margin_doctor'];

            $arr[] = $newItem;
        }

        return $arr;
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

    private function addTurnoverMarginStats($visit, $arr)
    {
        $newItem = [];

        $calcVisitStats = $this->visitRepository->calcVisitStats($visit);
        $exists = false;

        foreach ($arr as $key => $item) {
            if ($calcVisitStats['user_id'] == $item['user_id']) {

                //modyfikujemy dane
                $newItem = $item;

                $newItem['net_price'] += $calcVisitStats['net_price'];
                $newItem['gross_price'] += $calcVisitStats['gross_price'];
                $newItem['medicals_turnover'] += $calcVisitStats['medicals_turnover'];
                $newItem['medicals_margin_doctor_all'] += $calcVisitStats['medicals_margin_doctor_all'];
                $newItem['medicals_margin_company_all'] += $calcVisitStats['medicals_margin_company_all'];
                $newItem['medicals_margin_all'] += $calcVisitStats['medicals_margin_all'];
                $newItem['additional_services_turnover'] += $calcVisitStats['additional_services_turnover'];
                $newItem['additional_services_margin_doctor_all'] += $calcVisitStats['additional_services_margin_doctor_all'];
                $newItem['additional_services_margin_company_all'] += $calcVisitStats['additional_services_margin_company_all'];
                $newItem['additional_services_margin_all'] += $calcVisitStats['additional_services_margin_all'];

                // nadpisujemy całą tablicę
                $arr[$key] = $newItem;

                return $arr;
            }
        }

        // jeżeli nie było w tablicy to dodajemy całość
        if (!$exists) {
            $arr[] = $calcVisitStats;
        }

        return $arr;
    }

    private function addToMedicalStats($data, $arr)
    {
        //dd($data);
        $newData = [];
        $newData['medical_id'] = $data->medical_id;
        $newData['name'] = $data->medical->name;
        $newData['unit_measure_name'] = $data->medical->unit_measure->name;
        $newData['vat_price'] = $data->sum_gross_price - $data->sum_net_price;
        $newData['net_price'] = $data->sum_net_price;
        $newData['gross_price'] = $data->sum_gross_price;
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['medical_id'] == $data->medical_id) {
                $exists = true;

                $net_price = $arr[$key]['net_price'] + $data->sum_net_price;
                $gross_price = $arr[$key]['gross_price'] + $data->sum_gross_price;
                //vat value
                $vat_price = $gross_price - $net_price;

                $quantity = $arr[$key]['quantity'] + $data->quantity;

                $arr[$key]['vat_price'] = $vat_price;
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

        //cena wpisywana, np. paliwo
        if ($data->additionalservice->set_price_in_visit) {
            $net_price = $data->net_price;
            $gross_price = $data->gross_price;
        } else {
            $net_price = $data->quantity * $data->net_price;
            $gross_price = $data->quantity * $data->gross_price;
        }

        //calc values
        $newData['vat_price'] = $gross_price - $net_price;
        $newData['net_price'] = $net_price;
        $newData['gross_price'] = $gross_price;
        $newData['quantity'] = $data->quantity;

        // Check exists service in array
        $exists = false;
        foreach ($arr as $key => $value) {
            if ($value['additional_service_id'] == $data->additional_service_id && !$data->additionalservice->set_price_in_visit) {
                $exists = true;

                $net_price = $arr[$key]['net_price'] + ($data->quantity * $data->net_price);
                $gross_price = $arr[$key]['gross_price'] + ($data->quantity * $data->gross_price);
                $quantity = $arr[$key]['quantity'] + $data->quantity;
                //vat value
                $vat_price = $gross_price - $net_price;

                $newData['vat_price'] = $vat_price;
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
