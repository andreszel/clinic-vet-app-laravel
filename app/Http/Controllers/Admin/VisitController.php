<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdditionalServiceToVisit;
use App\Http\Requests\AddMedicalToVisit;
use App\Http\Requests\AddVisitStep1;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\AdditionalService;
use App\Models\Customer;
use App\Models\Medical;
use App\Models\PayTypes;
use App\Models\Vats;
use App\Models\Visit;
use App\Models\VisitAdditionalService;
use App\Models\VisitMedical;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VisitController extends Controller
{
    private VisitRepositoryInterface $visitRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository, VisitRepositoryInterface $visitRepository)
    {
        $this->userRepository = $userRepository;
        $this->visitRepository = $visitRepository;
    }

    public function index(Request $request): View
    {
        $user_id = $request->get('user_id');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $customer_name = $request->get('customer_name');
        $customer_surname = $request->get('customer_surname');

        $page = $request->get('page');
        $limit = $request->get('limit', VisitRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->visitRepository->filterBy($user_id, $from_date, $to_date, $customer_name, $customer_surname, $limit);
        $resultPaginator->appends([
            'user_id' => $user_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_name' => $customer_name,
            'customer_surname' => $customer_surname,
        ]);

        $counter = 1;
        if ($page >= 1) {
            $counter = (($page - 1) * $limit) + 1;
        }

        $users = $this->userRepository->all();


        return view('admin.visits.list', [
            'visits' => $resultPaginator,
            'users' => $users,
            'user_id' => $user_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_name' => $customer_name,
            'customer_surname' => $customer_surname,
            'counter' => $counter,
            'visitRepository' => $this->visitRepository
        ]);
    }

    /**
     * Show the form for add medicals to visit.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_new_visit(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);

        $postData = $request->all();
        $postData['customer_id'] = $customer->id;

        $visit_number = $this->visitRepository->maxVisitNumber($customer->id);
        $postData['visit_number'] = $visit_number;
        $postData['user_id'] = Auth::id();

        $visit = $this->visitRepository->create($postData);

        return redirect()->route('visits.step1', ['id' => $visit->id])->with('success', 'Wizyta dla klienta została utworzona! Wybierz teraz typ płatności i datę wizyty.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        return redirect()->route('visits.step1', ['id' => $id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function step1(Request $request, $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);
        /* 
        if (!$canManage) {
            return redirect()->route('visits.list')->with('warning', 'Wizyta nie może być już edytowana!');
        } */

        $currentStep = 1;
        $pay_types = PayTypes::get();
        $visit = Visit::findOrFail($id);
        $customer = Customer::findOrFail($visit->customer_id);
        $maxStep = VisitRepositoryInterface::MAX_STEP;

        return view('admin.visits.step1', [
            'currentStep' => $currentStep,
            'customer' => $customer,
            'maxStep' => $maxStep,
            'pay_types' => $pay_types,
            'visit' => $visit
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_step1(AddVisitStep1 $request_add, int $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $data = $request_add->validated();
        $this->visitRepository->update($data, $id);

        return redirect()->route('visits.step2', ['id' => $id])->with('success', 'Parametry podstawowe zostały zapisane!');
    }

    /**
     * Show the form for add medicals to visit.
     *
     * @return \Illuminate\Http\Response
     */
    public function step2(Request $request, $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $currentStep = 2;
        $visit = Visit::findOrFail($id);
        $customer = Customer::findOrFail($visit->customer_id);
        $maxStep = VisitRepositoryInterface::MAX_STEP;

        // Search
        $counter = 1;
        $counter_visit_medicals = 1;
        $sum_all_medicals = 0;
        $medicals = [];
        $visit_medicals = [];
        $phrase = $request->get('phrase');

        if (strlen($phrase) >= 2) {
            $query = Medical::with(['vat_buy', 'vat_sell', 'unit_measure'])
                ->where('active', true)
                ->orderBy('name');
            $query->whereRaw('name like ?', ["%$phrase%"]);

            $medicals = $query->get();
        }

        $visit_medicals = VisitMedical::with(['vat', 'medical.unit_measure'])
            ->where('visit_id', $id)
            ->get();

        $sum_all_medicals = $visit_medicals->sum('sum_gross_price');
        //dd($visit_medicals);

        return view('admin.visits.step2', [
            'counter' => $counter,
            'counter_visit_medicals' => $counter_visit_medicals,
            'currentStep' => $currentStep,
            'customer' => $customer,
            'maxStep' => $maxStep,
            'medicals' => $medicals,
            'sum_all_medicals' => $sum_all_medicals,
            'visit' => $visit,
            'visit_medicals' => $visit_medicals,
            'phrase' => $phrase
        ]);
    }

    public function add_medical(AddMedicalToVisit $request, $id, $medical_id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $postData =  $request->all();
        $quantity = $postData['quantity'];

        //pobieramy dane o leku
        $medical = Medical::findOrFail($medical_id);

        // ze względu na możliwość zmiany ceny musimy wyliczyć cenę sprzedaży oraz marżę wg nowej ceny brutto
        if ($medical->unit_measure->can_change_price) {
            $gross_price_sell = $postData['gross_price_sell'];
        } else {
            $gross_price_sell = $medical->gross_price_sell;
        }

        $net_price_sell =  $gross_price_sell / (1 + ($medical->vat_sell->name / 100));
        $net_margin = ($gross_price_sell - $medical->gross_price_buy);
        $gross_margin = ($net_margin * (1 + ($medical->vat_sell->name / 100)));


        if ($gross_price_sell < $medical->gross_price_sell) {
            return redirect()->back()->with('warning', 'Lek nie został dodany! Cena sprzedaży nie może być mniejsza od ceny zakupu.');
        }

        //sprawdzamy, czy już nie ma dodanego takiego leku
        $old_medical = VisitMedical::where('medical_id', $medical_id)
            ->where('visit_id', $postData['visit_id'])
            ->first();

        //jeżeli lek istnieje, dodajemy ilość, aktualizujemy wybrane pola i wykonujemy update
        if ($old_medical) {
            $quantity = ($old_medical->quantity + $postData['quantity']);

            //calc
            // cena zakupu
            $sum_gross_price_buy = ($quantity * $medical->gross_price_buy);
            $sum_net_price_buy = $sum_gross_price_buy / (1 + ($medical->vat_buy->name / 100));
            // cena sprzedaży
            $sum_gross_price_sell = ($quantity * $gross_price_sell);
            $sum_net_price_sell = $sum_gross_price_sell / (1 + ($medical->vat_sell->name / 100));
            // margin
            $sum_net_margin = $sum_net_price_sell - $sum_net_price_buy;
            $sum_gross_margin = $sum_gross_price_sell - $sum_gross_price_buy;

            $old_medical->quantity = $quantity;
            $old_medical->vat_id = $medical->vat_sell_id;
            $old_medical->net_price = $net_price_sell;
            $old_medical->gross_price = $gross_price_sell;
            $old_medical->net_margin = $net_margin;
            $old_medical->gross_margin = $gross_margin;

            // ustawiamy wartości, żeby później już nie obliczać
            $old_medical->sum_net_price = $sum_net_price_sell;
            $old_medical->sum_gross_price = $sum_gross_price_sell;
            $old_medical->sum_net_margin = $sum_net_margin;
            $old_medical->sum_gross_margin = $sum_gross_margin;

            $old_medical->update();
        } else {
            //calc
            // cena zakupu
            $sum_gross_price_buy = ($quantity * $medical->gross_price_buy);
            $sum_net_price_buy = $sum_gross_price_buy / (1 + ($medical->vat_buy->name / 100));
            // cena sprzedaży
            $sum_gross_price_sell = ($quantity * $gross_price_sell);
            $sum_net_price_sell = $sum_gross_price_sell / (1 + ($medical->vat_sell->name / 100));
            // margin
            $sum_net_margin = $sum_net_price_sell - $sum_net_price_buy;
            $sum_gross_margin = $sum_gross_price_sell - $sum_gross_price_buy;

            $visit_medical = new VisitMedical();

            $visit_medical->visit_id = $postData['visit_id'];
            $visit_medical->medical_id = $medical_id;
            $visit_medical->quantity = $quantity;
            $visit_medical->vat_id = $medical->vat_sell_id;
            $visit_medical->net_price = $net_price_sell;
            $visit_medical->gross_price = $gross_price_sell;
            $visit_medical->net_margin = $net_margin;
            $visit_medical->gross_margin = $gross_margin;

            // ustawiamy wartości, żeby później już nie obliczać
            $visit_medical->sum_net_price = $sum_net_price_sell;
            $visit_medical->sum_gross_price = $sum_gross_price_sell;
            $visit_medical->sum_net_margin = $sum_net_margin;
            $visit_medical->sum_gross_margin = $sum_gross_margin;

            $visit_medical->save();
        }

        return redirect()->back()->with('success', 'Lek został dodany!');
    }

    /**
     * Show the form for add medicals to visit.
     *
     * @return \Illuminate\Http\Response
     */
    public function step3(Request $request, $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $currentStep = 3;
        $visit = Visit::findOrFail($id);
        $customer = Customer::findOrFail($visit->customer_id);
        $maxStep = VisitRepositoryInterface::MAX_STEP;

        // Search
        $counter = 1;
        $counter_visit_additional_services = 1;
        $sum_all_additional_services = 0;
        $additional_services = [];
        $visit_additional_services = [];
        $phrase = $request->get('phrase');

        if (strlen($phrase) >= 2) {
            $query = AdditionalService::with(['vat'])
                ->where('active', true)
                ->orderBy('name');
            $query->whereRaw('name like ?', ["$phrase%"]);

            $additional_services = $query->get();
        }

        $visit_additional_services = VisitAdditionalService::with(['vat', 'additionalservice'])
            ->where('visit_id', $id)
            ->get();

        $sum_all_additional_services = $visit_additional_services->sum('sum_gross_price');


        return view('admin.visits.step3', [
            'counter' => $counter,
            'counter_visit_additional_services' => $counter_visit_additional_services,
            'currentStep' => $currentStep,
            'customer' => $customer,
            'maxStep' => $maxStep,
            'additional_services' => $additional_services,
            'sum_all_additional_services' => $sum_all_additional_services,
            'visit' => $visit,
            'visit_additional_services' => $visit_additional_services,
            'phrase' => $phrase
        ]);
    }

    public function add_additional_service(AddAdditionalServiceToVisit $request, $id, $additional_service_id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $postData =  $request->all();
        $quantity = (int)$postData['quantity'];

        //pobieramy dane o leku
        $additional_service = AdditionalService::where('id', $postData['additional_service_id'])
            ->where('active', 1)
            ->first();

        // pobieramy VAT, żeby ktoś nie dodał innego
        $vat = Vats::where('id', $additional_service->vat_id)->first();
        $vatDivisor  = 1 + ((int)$vat->name / 100);

        // Ceny z formularza
        $gross_price = $postData['gross_price'];
        $net_price = $gross_price / $vatDivisor;

        // Ceny z usługi
        $gross_price_std = $additional_service->gross_price;
        $net_price_std = $additional_service->net_price;

        if ($gross_price < $gross_price_std) {
            return redirect()->back()->with('warning', 'Usługa nie została dodana! Cena sprzedaży nie może być mniejsza od ceny w cenniku.');
        }

        // sprawdzamy, czy istnieje już taka usługa dodana do wizyty
        $old_visit_additional_service = VisitAdditionalService::where('additional_service_id', $additional_service->id)
            ->where('visit_id', $postData['visit_id'])
            ->first();

        //jeżeli istnieje i nie jest to paliwo to dodajemy ilość, aktualiujemy vat i ceny
        if ($old_visit_additional_service) {
            if ($additional_service->set_price_in_visit) {
                $newNetPrice = ($old_visit_additional_service->net_price + $net_price);
                $newGrossPrice = ($old_visit_additional_service->gross_price + $gross_price);

                // Calculations START
                $sum_gross_price = ($quantity * $newGrossPrice);
                $sum_net_price = $sum_gross_price / $vatDivisor;
                $sum_gross_price_std = 0;
                $sum_net_price_std = 0;

                // Additional margin, all for doctor
                $addtional_net_margin = 0;
                $addtional_gross_margin = 0;

                // Summary margin
                $sum_gross_margin_doctor = 0;
                $sum_net_margin_doctor = 0;
                $sum_gross_margin_company = $sum_gross_price;
                $sum_net_margin_company = $sum_net_price;
                // STOP

                // ustawiamy wartości
                $old_visit_additional_service->quantity = $quantity;
                $old_visit_additional_service->sum_net_price = $sum_net_price;
                $old_visit_additional_service->sum_gross_price = $sum_gross_price;
                $old_visit_additional_service->vat_id = $additional_service->vat_id;
                $old_visit_additional_service->net_price = $newNetPrice;
                $old_visit_additional_service->gross_price = $newGrossPrice;
                $old_visit_additional_service->net_price_std = $net_price_std;
                $old_visit_additional_service->gross_price_std = $gross_price_std;
                $old_visit_additional_service->sum_net_margin_company = $sum_net_margin_company;
                $old_visit_additional_service->sum_gross_margin_company = $sum_gross_margin_company;
                $old_visit_additional_service->sum_net_margin_doctor = $sum_net_margin_doctor;
                $old_visit_additional_service->sum_gross_margin_doctor = $sum_gross_margin_doctor;

                //dd($old_visit_additional_service);
                $old_visit_additional_service->update();
            } else {
                $quantity += $old_visit_additional_service->quantity;

                // Calculations START
                $sum_gross_price = ($quantity * $gross_price);
                $sum_net_price = $sum_gross_price / $vatDivisor;
                $sum_gross_price_std = ($quantity * $gross_price_std);
                $sum_net_price_std = $sum_gross_price_std / $vatDivisor;

                // Additional margin, all for doctor
                $addtional_net_margin = $sum_net_price - $sum_net_price_std;
                $addtional_gross_margin = $sum_gross_price - $sum_gross_price_std;

                // wysokość marży [%] za usługi dla lekarza, który dodaje wizytę
                $commission_medicals = $visitModel->user->commission_medicals;
                $commission_services = $visitModel->user->commission_services;

                // Summary margin
                $sum_gross_margin_doctor = ($sum_gross_price_std * ($commission_services / 100));
                $sum_net_margin_doctor = ($sum_gross_margin_doctor / $vatDivisor);
                $sum_gross_margin_company = ($sum_gross_price_std - $sum_gross_margin_doctor);
                $sum_net_margin_company = ($sum_gross_margin_company / $vatDivisor);

                // Plus additional margin
                $sum_net_margin_doctor += $addtional_net_margin;
                $sum_gross_margin_doctor +=  $addtional_gross_margin;
                // STOP

                // ustawiamy wartości
                $old_visit_additional_service->quantity = $quantity;
                $old_visit_additional_service->vat_id = $additional_service->vat_id;
                $old_visit_additional_service->net_price = $net_price;
                $old_visit_additional_service->gross_price = $gross_price;
                $old_visit_additional_service->sum_net_price = $sum_net_price;
                $old_visit_additional_service->sum_gross_price = $sum_gross_price;

                $old_visit_additional_service->net_price_std = $net_price_std;
                $old_visit_additional_service->gross_price_std = $gross_price_std;
                $old_visit_additional_service->sum_net_margin_company = $sum_net_margin_company;
                $old_visit_additional_service->sum_gross_margin_company = $sum_gross_margin_company;
                $old_visit_additional_service->sum_net_margin_doctor = $sum_net_margin_doctor;
                $old_visit_additional_service->sum_gross_margin_doctor = $sum_gross_margin_doctor;

                $old_visit_additional_service->update();
            }
        } else {
            $visit_additional_service = new VisitAdditionalService();

            // Calculations START
            $sum_gross_price = ($quantity * $gross_price);
            $sum_net_price = $sum_gross_price / $vatDivisor;
            $sum_gross_price_std = ($quantity * $gross_price_std);
            $sum_net_price_std = $sum_gross_price_std / $vatDivisor;

            if ($additional_service->set_price_in_visit) {
                // Additional margin, all for doctor
                $addtional_net_margin = 0;
                $addtional_gross_margin = 0;

                // Summary margin
                $sum_gross_margin_doctor = 0;
                $sum_net_margin_doctor = 0;
                $sum_gross_margin_company = $sum_gross_price;
                $sum_net_margin_company = $sum_net_price;
                // STOP
            } else {
                // Additional margin, all for doctor
                $addtional_net_margin = $sum_net_price - $sum_net_price_std;
                $addtional_gross_margin = $sum_gross_price - $sum_gross_price_std;

                // wysokość marży [%] za usługi dla lekarza, który dodaje wizytę
                $commission_medicals = $visitModel->user->commission_medicals;
                $commission_services = $visitModel->user->commission_services;

                // Summary margin
                $sum_gross_margin_doctor = ($sum_gross_price_std * ($commission_services / 100));
                $sum_net_margin_doctor = ($sum_gross_margin_doctor / $vatDivisor);
                $sum_gross_margin_company = ($sum_gross_price_std - $sum_gross_margin_doctor);
                $sum_net_margin_company = ($sum_gross_margin_company / $vatDivisor);

                // Plus additional margin
                $sum_net_margin_doctor += $addtional_net_margin;
                $sum_gross_margin_doctor +=  $addtional_gross_margin;
                // STOP
            }


            // ustawiamy wartości
            $visit_additional_service->visit_id = $postData['visit_id'];
            $visit_additional_service->additional_service_id = $additional_service->id;
            $visit_additional_service->quantity = $quantity;
            $visit_additional_service->vat_id = $additional_service->vat_id;
            $visit_additional_service->net_price = $net_price;
            $visit_additional_service->gross_price = $gross_price;
            $visit_additional_service->sum_net_price = $sum_net_price;
            $visit_additional_service->sum_gross_price = $sum_gross_price;

            $visit_additional_service->net_price_std = $net_price_std;
            $visit_additional_service->gross_price_std = $gross_price_std;
            $visit_additional_service->sum_net_margin_company = $sum_net_margin_company;
            $visit_additional_service->sum_gross_margin_company = $sum_gross_margin_company;
            $visit_additional_service->sum_net_margin_doctor = $sum_net_margin_doctor;
            $visit_additional_service->sum_gross_margin_doctor = $sum_gross_margin_doctor;

            //dd($visit_additional_service);
            $visit_additional_service->save();
        }

        return redirect()->back()->with('success', 'Usługa dodatkowa została dodana!');
    }

    /**
     * Show the form for add medicals to visit.
     *
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request, $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $currentStep = 4;
        $visitModel = Visit::findOrFail($id);
        $customer = Customer::findOrFail($visitModel->customer_id);
        $maxStep = VisitRepositoryInterface::MAX_STEP;
        $maxTimeToEdit = VisitRepositoryInterface::MAX_TIME_TO_EDIT;

        // Search
        $counter = 1;
        $sum_all_medicals = 0;
        $sum_all_additional_services = 0;
        $visit_medicals = [];

        $visit_medicals = VisitMedical::with(['vat', 'medical.unit_measure'])
            ->where('visit_id', $id)
            ->get();

        $visit_additional_services = VisitAdditionalService::with(['vat', 'additionalservice'])
            ->where('visit_id', $id)
            ->get();

        $sum_all_medicals = $visit_medicals->sum('sum_gross_price');
        $sum_all_additional_services = $visit_additional_services->sum('sum_gross_price');

        return view('admin.visits.summary', [
            'counter' => $counter,
            'currentStep' => $currentStep,
            'maxStep' => $maxStep,
            'customer' => $customer,
            'visit' => $visitModel,
            'visit_medicals' => $visit_medicals,
            'sum_all_medicals' => $sum_all_medicals,
            'visit_additional_services' => $visit_additional_services,
            'sum_all_additional_services' => $sum_all_additional_services,
            'maxTimeToEdit' => $maxTimeToEdit
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_summary(Request $request, int $id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = $this->visitRepository->get($id);
        Gate::authorize('view', $visitModel);

        $postData =  $request->all();
        $sum_net_price = 0;
        $sum_gross_price = 0;

        $visit_medicals = VisitMedical::with(['vat', 'medical.unit_measure'])
            ->where('visit_id', $id)
            ->get();
        foreach ($visit_medicals as $visit_medical) {
            $sum_net_price += $visit_medical->sum_net_price;
            $sum_gross_price += $visit_medical->sum_gross_price;
        }

        $visit_additional_services = VisitAdditionalService::with(['vat', 'additionalservice'])
            ->where('visit_id', $id)
            ->get();
        foreach ($visit_additional_services as $visit_additional_service) {
            $sum_net_price += $visit_additional_service->sum_net_price;
            $sum_gross_price += $visit_additional_service->sum_gross_price;
        }

        $postData['net_price'] = $sum_net_price;
        $postData['gross_price'] = $sum_gross_price;
        $postData['confirm_visit'] = true;
        $postData['paid_gross_price'] = number_format($postData['paid_gross_price'], 2, '.', '');

        $this->visitRepository->update($postData, $id);

        return redirect()->route('visits.list')->with('success', 'Wizyta została zapisana!');
        //$this->visitRepository->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$canManage = $this->visitRepository->canManageVisit($id);
        $visitModel = Visit::findOrFail($id);
        Gate::authorize('view', $visitModel);

        if (!$visitModel->confirm_visit) {
            $this->visitRepository->delete($id);
            return redirect()->back()->with('success', 'Wizyta lekarska została usunięta!');
        } else {
            $this->visitRepository->delete($id);
            return redirect()->back()->with('error', 'Wizyta lekarska nie została usunięta! Zatwierdzonych wizyt nie można usuwać.');
        }
    }

    public function remove_additional_service($visit_id, $id)
    {
        VisitAdditionalService::where('visit_id', $visit_id)->find($id)->delete();

        return redirect()->back()->with('success', 'Usługa została usunięta!');
    }


    public function remove_medical($visit_id, $id)
    {
        VisitMedical::where('visit_id', $visit_id)->find($id)->delete();

        return redirect()->back()->with('success', 'Lek został usunięty!');
    }
}
