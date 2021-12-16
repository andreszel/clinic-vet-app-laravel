<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMedical;
use App\Interfaces\MedicalRepositoryInterface;
use App\Models\UnitMeasure;
use App\Models\Vats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\Console\Input\Input;

class MedicalController extends Controller
{
    private MedicalRepositoryInterface $medicalRepository;

    public function __construct(MedicalRepositoryInterface $medicalRepository)
    {
        $this->medicalRepository = $medicalRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $phrase = $request->get('phrase');
        $limit = $request->get('limit', MedicalRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->medicalRepository->filterBy($phrase, $limit);
        $resultPaginator->appends([
            'phrase' => $phrase
        ]);

        return view('admin.medicals.list', [
            'medicals' => $resultPaginator,
            'phrase' => $phrase
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
        $unit_measures = UnitMeasure::get();

        return view('admin.medicals.create', [
            'vats' => $vats,
            'unit_measures' => $unit_measures
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddMedical $request)
    {
        // zalogowany użytkownik
        $user_id = Auth::id();

        $vat_buy = Vats::where('id', $request->vat_buy_id)->first();
        $vat_sell = Vats::where('id', $request->vat_sell_id)->first();

        $data = $request->validated();

        if ($request->net_price_buy != NULL) {
            dump('net price buy ok!!');
            $gross_price_buy = number_format(($request->net_price_buy * (1 + ($vat_buy->name / 100))), 2, '.', '');
            $request['gross_price_buy'] = $gross_price_buy;
        } else {
            dump('net gross buy ok!!');
            //$request->net_price_buy = number_format(($request->gross_price_buy / (1 + ($vat_buy / 100))), 2, '.','');
            $net_price_buy = number_format(($request->gross_price_buy / (1 + ($vat_buy->name / 100))), 2, '.', '');
            $request['net_price_buy'] = $net_price_buy;
        }

        if ($request->gross_price_sell != NULL) {
            dump('gross price buy ok!!');
            $net_price_sell = number_format(($request->gross_price_sell / (1 + ($vat_buy->sell / 100))), 2, '.', '');
            $request['gross_price_buy'] = $net_price_sell;
        } else {
            dump('net gross buy ok!!');
            //$request->net_price_buy = number_format(($request->gross_price_buy / (1 + ($vat_buy / 100))), 2, '.','');
            $net_price_buy = number_format(($request->gross_price_sell / (1 + ($vat_sell->name / 100))), 2, '.', '');
            $request['net_price_buy'] = $net_price_buy;
        }

        dd($request->all());

        dump(empty($request->net_price_buy) and empty($request->gross_price_buy == NULL));
        if ($request->net_price_buy == NULL and $request->gross_price_buy == NULL) {
            return redirect()->route('medicals.create')->with('warning', 'Lek weterynaryjny nie został dodany, przesłane parametry były nieprawidłowe!');
        }

        if ($request->net_price_sell == NULL and $request->gross_price_sell == NULL) {
            return redirect()->route('medicals.create')->with('warning', 'Lek weterynaryjny nie został dodany, przesłane parametry były nieprawidłowe!');
        }



        $medical = $this->medicalRepository->create($data);

        return redirect()->route('medicals.edit', ['id' => $medical->id])->with('success', 'Lek weterynaryjny został dodany!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
