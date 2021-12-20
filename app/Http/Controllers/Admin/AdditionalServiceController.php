<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdditionalServices;
use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Models\AdditionalService;
use App\Models\Customer;
use App\Models\Vats;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdditionalServiceController extends Controller
{
    private AdditionalServiceRepositoryInterface $additionalServiceRepository;

    public function __construct(AdditionalServiceRepositoryInterface $additionalServiceRepository)
    {
        $this->additionalServiceRepository = $additionalServiceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $page = $request->get('page');
        $limit = $request->get('limit', AdditionalServiceRepositoryInterface::LIMIT_DEFAULT);
        $additionalservices = $this->additionalServiceRepository->allPaginated($limit);

        $counter = ($page * $limit) + 1;

        return view('admin.additionalservices.list', [
            'additionalservices' => $additionalservices,
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vats = Vats::get();

        return view('admin.additionalservices.create', [
            'vats' => $vats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAdditionalServices $request)
    {
        $data = $request->validated();
        $vat = Vats::where('id', $request['vat_id'])->first();
        $vatDivisor  = 1 + ((int)$vat->name / 100);
        $netPrice = number_format($request['gross_price'] / $vatDivisor, 2);
        $data['net_price'] = $netPrice;

        $additionalservices = $this->additionalServiceRepository->create($data);

        return redirect()->route('additionalservices.list')->with('success', 'Usługa dodatkowa została dodana!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $additionalservice = AdditionalService::with(['vat'])->find($id);
        $vats = Vats::get();

        return view('admin.additionalservices.edit', [
            'additionalservice' => $additionalservice,
            'vats' => $vats
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddAdditionalServices $request, int $id)
    {
        $data = $request->validated();
        $vat = Vats::where('id', $request['vat_id'])->first();
        $vatDivisor  = 1 + ((int)$vat->name / 100);
        $netPrice = number_format($request['gross_price'] / $vatDivisor, 2);
        $data['net_price'] = $netPrice;
        dd($data);
        $this->additionalServiceRepository->update($data, $id);

        return redirect()->route('additionalservices.list')->with('success', 'Usługa dodatkowa została zaktualizowana!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->additionalServiceRepository->delete($id);
        return redirect()->back()->with('success', 'Usługa dodatkowa została usunięta!');
    }

    public function changeStatus(int $id)
    {
        $this->additionalServiceRepository->change_status($id);

        return redirect()->back()->with('success', 'Status został zmieniony!');
    }
}
