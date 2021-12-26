<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdditionalServiceToVisit;
use App\Http\Requests\AddMedicalToVisit;
use App\Http\Requests\AddVisitStep1;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\AdditionalService;
use App\Models\Customer;
use App\Models\Medical;
use App\Models\PayTypes;
use App\Models\Vats;
use App\Models\Visit;
use App\Models\VisitAdditionalService;
use App\Models\VisitMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VisitController extends Controller
{
    private VisitRepositoryInterface $visitRepository;

    public function __construct(VisitRepositoryInterface $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    public function index(Request $request): View
    {
        $phrase = $request->get('phrase');
        $page = $request->get('page');
        $limit = $request->get('limit', VisitRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->visitRepository->filterBy($phrase, $limit);
        $resultPaginator->appends([
            'phrase' => $phrase
        ]);

        $counter = ($page * $limit) + 1;

        return view('admin.visits.list', [
            'visits' => $resultPaginator,
            'phrase' => $phrase,
            'counter' => $counter
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

        return redirect()->route('visits.step1', ['id' => $visit->id])->with('success', 'Wizyta dla klienta {{ $customer->name }} {{ $customer->surname }} została utworzona! Wybierz teraz typ płatności i datę wizyty.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function step1(Request $request, $id)
    {
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
            $query->whereRaw('name like ?', ["$phrase%"]);

            $medicals = $query->get();
        }

        $visit_medicals = VisitMedical::with(['vat', 'medical.unit_measure'])
            ->where('visit_id', $id)
            ->get();
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
        $postData =  $request->all();

        //pobieramy dane o leku
        $medical = Medical::findOrFail($postData['medical_id']);

        $visit_medical = new VisitMedical();

        $visit_medical->visit_id = $postData['visit_id'];
        $visit_medical->medical_id = $postData['medical_id'];
        $visit_medical->quantity = $postData['quantity'];
        $visit_medical->vat_id = $medical->vat_sell_id;
        $visit_medical->net_price = $medical->net_price_sell;
        $visit_medical->gross_price = $medical->gross_price_sell;

        $visit_medical->save();

        return redirect()->back()->with('success', 'Lek został dodany!');
    }

    /**
     * Show the form for add medicals to visit.
     *
     * @return \Illuminate\Http\Response
     */
    public function step3(Request $request, $id)
    {
        $currentStep = 4;
        $visit = Visit::findOrFail($id);
        $customer = Customer::findOrFail($visit->customer_id);
        $maxStep = VisitRepositoryInterface::MAX_STEP;

        // Search
        $counter = 1;
        $counter_visit_services = 1;
        $sum_all_services = 0;
        $additional_services = [];
        $visit_services = [];
        $phrase = $request->get('phrase');

        if (strlen($phrase) >= 2) {
            $query = AdditionalService::with(['vat'])
                ->where('active', true)
                ->orderBy('name');
            $query->whereRaw('name like ?', ["$phrase%"]);

            $additional_services = $query->get();
        }

        $visit_services = VisitAdditionalService::with(['vat', 'additionalservice'])
            ->where('visit_id', $id)
            ->get();
        //dd($visit_services);

        return view('admin.visits.step3', [
            'counter' => $counter,
            'counter_visit_services' => $counter_visit_services,
            'currentStep' => $currentStep,
            'customer' => $customer,
            'maxStep' => $maxStep,
            'additional_services' => $additional_services,
            'sum_all_services' => $sum_all_services,
            'visit' => $visit,
            'visit_services' => $visit_services,
            'phrase' => $phrase
        ]);
    }

    public function add_additional_service(AddAdditionalServiceToVisit $request, $id, $additional_service_id)
    {
        $postData =  $request->all();

        //pobieramy dane o leku
        $additional_service = AdditionalService::where('id', $postData['additional_service_id'])
            ->where('active', 1)
            ->first();

        $grossPrice = ($additional_service->set_price_in_visit ? $postData['gross_price'] : $additional_service->gross_price);
        $vat = Vats::where('id', $additional_service->vat_id)->first();
        $vatDivisor  = 1 + ((int)$vat->name / 100);
        $netPrice = number_format($grossPrice / $vatDivisor, 2);

        $visit_additional_service = new VisitAdditionalService();

        $visit_additional_service->visit_id = $postData['visit_id'];
        $visit_additional_service->additional_service_id = $additional_service->id;
        $visit_additional_service->quantity = $postData['quantity'];
        $visit_additional_service->vat_id = $additional_service->vat_id;
        $visit_additional_service->net_price = $netPrice;
        $visit_additional_service->gross_price = $grossPrice;
        //dd($visit_additional_service);
        $visit_additional_service->save();

        return redirect()->back()->with('success', 'Usługa dodatkowa została dodana!');
    }
}
