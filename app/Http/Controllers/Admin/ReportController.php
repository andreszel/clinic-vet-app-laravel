<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReportController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;
    private UserRepositoryInterface $userRepository;
    private VisitRepositoryInterface $visitRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository, UserRepositoryInterface $userRepository, VisitRepositoryInterface $visitRepository)
    {
        $this->customerRepository = $customerRepository;
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
        $customer_name = $request->get('customer_name');
        $customer_surname = $request->get('customer_surname');
        $paramsCount = 0;

        $user_id ? $paramsCount++ : null;
        $from_date ? $paramsCount++ : null;
        $to_date ? $paramsCount++ : null;
        $customer_name ? $paramsCount++ : null;
        $customer_surname ? $paramsCount++ : null;

        // Jeżeli nie ma ustawionych daty od i daty do to ustawiamy domyślne
        if ($paramsCount == 0) {
            $from_date = Carbon::now()->startOfMonth()->timezone('Europe/Warsaw')->toDateString();
            //$to_date = Carbon::today()->timezone('Europe/Warsaw')->toDateString();
            $to_date = Carbon::now()->endOfMonth()->timezone('Europe/Warsaw')->toDateString();
        }

        // Raport 1: Statystyka wizyt wszystkich lekarzy - statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        $visit_stats = [];
        $sum_visit_stats = [];

        // Raport 2: Statystyka leków i usług dodatkowych
        // Lista leków
        $medical_stats = [];
        $medical_stats_vat_price_sum = 0;
        $medical_stats_net_price_sum = 0;
        $medical_stats_gross_price_sum = 0;
        $medical_stats_paid_price_sum = 0;
        $services_medicals_stats_gross_price_sum = 0;
        // Lista usług dodatkowych
        $additional_service_stats = [];
        $additional_service_stats_vat_price_sum = 0;
        $additional_service_stats_net_price_sum = 0;
        $additional_service_stats_gross_price_sum = 0;

        // Raport 3: Statystyka obrotów i zysku
        $turnover_margin_stats = [];
        $turnover_margin_stats_sum = [];

        // Raport 4. Link do Szczegółowe rozliczenie wizyt wszystkich lekarzy
        $visit_calc_details = [];

        // Raport 5. Rozliczenie wizyt
        $visit_stats_by_pay_type = [];
        $summary_visit_stats_by_pay_type = [];

        // Raport 6: Statystyka wizyt klientów - statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
        $visit_customer_stats = [];
        $sum_visit_customer_stats = [];

        // get params from url
        $search_params = $this->getCurrentParams($request);

        /* $url = $request->url();
        $uri = $request->getRequestUri();
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

        // Wyszukiwarka
        //$limit = $request->get('limit', VisitRepositoryInterface::LIMIT_DEFAULT);
        $limit = 10000;
        $resultPaginator = $this->visitRepository->filterBy($user_id, $from_date, $to_date, $customer_name, $customer_surname, $limit);
        $resultPaginator->appends([
            'user_id' => $user_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_name' => $customer_name,
            'customer_surname' => $customer_surname,
        ]);

        // All users
        $users = $this->userRepository->whereType(2)->all();

        /**
         * Raport 1: Statystyka wizyt wszystkich lekarzy - statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
         */
        foreach ($users as $user) {
            $item = [];
            // doctor name
            $item['id'] = $user->id;
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

        // ----------------------------------------------------------------------------------------------
        // ----------------------------------------------------------------------------------------------

        /**
         * Raport 6: Statystyka wizyt klientów - statystyka wizyt za ostatni 1 rok, 6 miesięcy, 3 miesiące, 1 miesiąc, 1 tydzień, 1 dzień
         */
        $uniqueCustomers = $this->getUniqueCustomersFromVisits($resultPaginator);

        foreach ($uniqueCustomers as $key => $customer) {
            $item = [];
            // doctor name
            $item['id'] = $customer->id;
            $item['name'] = $customer->name;
            $item['surname'] = $customer->surname;

            // get stats
            $customerStats = $this->visitCustomerStats($customer->id);
            $item['stats'] = $customerStats;


            $visit_customer_stats[] = $item;

            // sumujemy wszystkie wizyty dla wszystkich lekarzy z danego okresu
            $sum_visit_customer_stats['last_year'] = (isset($sum_visit_customer_stats['last_year']) ? (int)$sum_visit_customer_stats['last_year'] + $customerStats['last_year'] : $customerStats['last_year']);
            $sum_visit_customer_stats['last_six_months'] = (isset($sum_visit_customer_stats['last_six_months']) ? (int)$sum_visit_customer_stats['last_six_months'] + $customerStats['last_six_months'] : $customerStats['last_six_months']);
            $sum_visit_customer_stats['last_three_months'] = (isset($sum_visit_customer_stats['last_three_months']) ? (int)$sum_visit_customer_stats['last_three_months'] + $customerStats['last_three_months'] : $customerStats['last_three_months']);
            $sum_visit_customer_stats['last_month'] = (isset($sum_visit_customer_stats['last_month']) ? (int)$sum_visit_customer_stats['last_month'] + $customerStats['last_month'] : $customerStats['last_month']);
            $sum_visit_customer_stats['last_week'] = (isset($sum_visit_customer_stats['last_week']) ? (int)$sum_visit_customer_stats['last_week'] + $customerStats['last_week'] : $customerStats['last_week']);
            $sum_visit_customer_stats['today'] = (isset($sum_visit_customer_stats['today']) ? (int)$sum_visit_customer_stats['today'] + $customerStats['today'] : $customerStats['today']);
        }

        // ----------------------------------------------------------------------------------------------
        // ----------------------------------------------------------------------------------------------

        foreach ($resultPaginator as $visit) {

            $medical_stats_paid_price_sum += $visit->paid_gross_price;

            foreach ($visit->visit_medicals as $visit_medical) {
                $vat_price = $visit_medical->sum_gross_price - $visit_medical->sum_net_price;

                // sumowanie cen - Raport 2: Statystyka leków i usług dodatkowych - lista leków
                $medical_stats_vat_price_sum += $vat_price;
                $medical_stats_net_price_sum += $visit_medical->sum_net_price;
                $medical_stats_gross_price_sum += $visit_medical->sum_gross_price;

                // Wiersze z lekami - Raport 2: Statystyka leków i usług dodatkowych - lista leków
                $medical_stats = $this->addToMedicalStats($visit_medical, $medical_stats);
            }

            foreach ($visit->additional_services as $additional_service) {
                $vat_price = $additional_service->sum_gross_price - $additional_service->sum_net_price;

                // sumowanie cen - Raport 2: Statystyka leków i usług dodatkowych - lista usług
                $additional_service_stats_vat_price_sum += $vat_price;
                $additional_service_stats_net_price_sum += $additional_service->sum_net_price;
                $additional_service_stats_gross_price_sum += $additional_service->sum_gross_price;

                // Wierwsze z usługami dodatkowymi - Raport 2: Statystyka leków i usług dodatkowych - lista usług
                $additional_service_stats = $this->addToAdditionalServiceStats($additional_service, $additional_service_stats);
            }

            // Raport 3: Statystyka obrotów i zysku
            $turnover_margin_stats = $this->visitRepository->addTurnoverMarginStats($visit, $turnover_margin_stats);
        }

        // Raport 2: Statystyka leków i usług dodatkowych - podsumowanie
        $services_medicals_stats_gross_price_sum = $medical_stats_gross_price_sum + $additional_service_stats_gross_price_sum;

        // sumowanie - Raport 3: Statystyka obrotów i zysku
        $turnover_margin_stats_sum = $this->visitRepository->sumTurnoverMarginStats($turnover_margin_stats);

        // sum - Raport 5. Rozliczenie wizyt
        foreach ($resultPaginator as $visit) {
            $visit_stats_by_pay_type = $this->getVisitStatsByPayType($visit, $visit_stats_by_pay_type);
            $summary_visit_stats_by_pay_type = $this->getSummaryVisitStatsByPayType($visit, $summary_visit_stats_by_pay_type);

            // Raport 4. Szczegółowe rozliczenie wizyt wszystkich lekarzy za okres od 2021-11-01 do 2021-11-30
            $visit_calc_details = $this->visitRepository->getVisitCalcDetails($visit, $visit_calc_details);
        }

        $visit_calc_details = $this->visitRepository->addSummaryVisitToCalcDetails($visit_calc_details);

        return view(
            'admin.reports.list',
            [
                'visits' => $resultPaginator,
                'users' => $users,
                'user_id' => $user_id,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'customer_name' => $customer_name,
                'customer_surname' => $customer_surname,
                'counter' => 1,
                'medical_stats' => $medical_stats,
                'medical_stats_vat_price_sum' => $medical_stats_vat_price_sum,
                'medical_stats_net_price_sum' => $medical_stats_net_price_sum,
                'medical_stats_gross_price_sum' => $medical_stats_gross_price_sum,
                'medical_stats_paid_price_sum' => $medical_stats_paid_price_sum,
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
                'visit_customer_stats' => $visit_customer_stats,
                'sum_visit_customer_stats' => $sum_visit_customer_stats,
                'visit_calc_details' => $visit_calc_details,
                'search_params' => $search_params
            ]
        );
    }

    public function createPDFOneVisit($id)
    {
        // use the facade
        $filename = 'wizyta_lekarska.pdf';

        $visit = $this->visitRepository->get($id);

        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'debugCss' => false, 'debugLayout' => false]);
        //$pdf->setPaper('a4', 'landscape');
        $pdf->setWarnings(false);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('admin.reports.pdf.oneVisit', ['visit' => $visit]);

        return $pdf->stream();
        //return $pdf->download($filename);
    }

    public function createPDFCustomerMonth(Request $request, $id, $from_date, $to_date)
    {
        $visits = $this->visitRepository->filterByCustomerDates($id, $from_date, $to_date);
        $visits->appends([
            'id' => $id,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
        $counter = 1;

        $customer = $this->customerRepository->get($id);

        // use the facade
        $filename = 'raport_dla_klienta.pdf';
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'debugCss' => false, 'debugLayout' => false]);
        //$pdf->setPaper('a4', 'landscape');
        $pdf->setWarnings(false);
        $pdf->loadView('admin.reports.pdf.customer', [
            'visits' => $visits,
            'customer' => $customer,
            'counter' => $counter,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);

        return $pdf->stream();
        //return $pdf->download($filename);
    }

    public function createPDFUserMonth(Request $request, $id, $from_date, $to_date)
    {
        $visits = $this->visitRepository->filterByUserDates($id, $from_date, $to_date);
        $visits->appends([
            'id' => $id,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);

        $stats = [];
        $turnover_margin_stats = [];
        $turnover_margin_stats_sum = [];
        foreach ($visits as $visit) {
            $calcVisitStats = $this->visitRepository->calcVisitStats($visit);
            $turnover_margin_stats = $this->visitRepository->addTurnoverMarginStats($visit, $turnover_margin_stats);
            $visit['stats'] = $calcVisitStats;
        }
        // sumowanie - Statystyka obrotów i zysku
        $turnover_margin_stats_sum = $this->visitRepository->sumTurnoverMarginStats($turnover_margin_stats);

        $counter = 1;

        $user = $this->userRepository->get($id);

        // use the facade
        $filename = 'raport_dla_lekarza.pdf';
        $data = array();
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'debugCss' => false, 'debugLayout' => false, 'margin-bottom' => 15]);
        //$pdf->setPaper('a4', 'landscape');
        $pdf->setWarnings(false);
        $pdf->loadView('admin.reports.pdf.user', [
            'visits' => $visits,
            'visit_count' => $visits->count(),
            'user' => $user,
            'counter' => $counter,
            'turnover_margin_stats' => $turnover_margin_stats,
            'turnover_margin_stats_sum' => $turnover_margin_stats_sum,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);

        return $pdf->stream();
        //return $pdf->download($filename);
    }

    private function getVisitStatsByPayType($visit, $arr)
    {
        $newItem = [];

        $calcVisitStats = $this->visitRepository->calcVisitStats($visit);

        $newItem['id'] = $visit->id;
        $newItem['visit_number'] = $visit->visit_number;
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
        $query = Visit::with(['user'])
            ->whereRaw('visit_date >= ?', [$start])
            ->whereRaw('visit_date <= ?', [$end])
            ->whereRaw('user_id = ?', [$user_id]);
        return $query->count();
    }

    private function visitCustomerStats($customer_id)
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


        $stats['last_year'] = $this->countCustomerVisits($customer_id, $start_year, $end);
        $stats['last_six_months'] = $this->countCustomerVisits($customer_id, $start_six_months, $end);
        $stats['last_three_months'] = $this->countCustomerVisits($customer_id, $start_three_months, $end);
        $stats['last_month'] = $this->countCustomerVisits($customer_id, $start_one_month, $end);
        $stats['last_week'] = $this->countCustomerVisits($customer_id, $start_one_week, $end);
        $stats['today'] = $this->countCustomerVisits($customer_id, $end, $end);

        return $stats;
    }

    private function countCustomerVisits($customer_id, $start, $end)
    {
        $query = Visit::with(['user'])
            ->whereRaw('visit_date >= ?', [$start])
            ->whereRaw('visit_date <= ?', [$end])
            ->whereRaw('customer_id = ?', [$customer_id]);
        return $query->count();
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

    private function getUniqueCustomersFromVisits($visits)
    {
        $tempUniqueId = [];
        $uniqueCustomers = [];

        foreach ($visits as $key => $visit) {
            //dump($visit->customer);
            if (!in_array($visit->customer->id, $tempUniqueId)) {
                array_push($uniqueCustomers, $visit->customer);
                array_push($tempUniqueId, $visit->customer->id);
            }
        }
        return $uniqueCustomers;
    }

    private function getCurrentParams($request)
    {
        $params_arr = [];
        $separator = '&';
        $uri = $request->getRequestUri();
        $uri_arr = explode('?', $uri);
        if (key_exists(1, $uri_arr)) {
            $params_arr = explode($separator, $uri_arr[1]);
            array_splice($params_arr, 0, 1);
            foreach ($params_arr as $key => $value) {
                $val_arr = explode('=', $value);
                $params_arr[$key] = array($val_arr[0] => $val_arr[1]);
            }
        }

        return $params_arr;
    }
}
