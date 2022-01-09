<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMedical;
use App\Interfaces\MedicalRepositoryInterface;
use App\Models\Medical;
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
        $page = (int)$request->get('page');
        $limit = $request->get('limit', MedicalRepositoryInterface::LIMIT_DEFAULT);

        $resultPaginator = $this->medicalRepository->filterBy($phrase, $limit);
        $resultPaginator->appends([
            'phrase' => $phrase
        ]);

        $counter = 1;
        if ($page >= 1) {
            $counter = (($page - 1) * $limit) + 1;
        }


        return view('admin.medicals.list', [
            'medicals' => $resultPaginator,
            'phrase' => $phrase,
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
        //$user_id = Auth::id();
        $data = $request->validated();

        $postData = $this->checkData($request->all());

        // Sprawdzamy, czy na pewno są wszystkie dane
        if (!$request['net_price_buy'] > 0 or !$request['gross_price_buy'] > 0) {
            return redirect()->route('medicals.create')->with('danger', 'Brak ceny zakupu netto i brutto. Lek nie został dodany!');
        }
        if (!$request['net_price_sell'] > 0 or !$request['gross_price_sell'] > 0) {
            return redirect()->route('medicals.create')->with('danger', 'Brak ceny sprzedaży netto i brutto. Lek nie został dodany!');
        }

        $this->medicalRepository->create($postData);

        return redirect()->route('medicals.list')->with('success', 'Lek weterynaryjny został dodany!');
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
        $medical = Medical::with(['vat_buy', 'vat_sell', 'unit_measure'])->find($id);
        $vats = Vats::get();
        $unit_measures = UnitMeasure::get();

        return view('admin.medicals.edit', [
            'medical' => $medical,
            'vats' => $vats,
            'unit_measures' => $unit_measures
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddMedical $request, int $id)
    {
        $data = $request->validated();

        $postData = $this->checkData($request->all());

        // Sprawdzamy, czy na pewno są wszystkie dane
        if (!$request['net_price_buy'] > 0 or !$request['gross_price_buy'] > 0) {
            return redirect()->route('medicals.create')->with('danger', 'Brak ceny zakupu netto i brutto. Lek nie został dodany!');
        }
        if (!$request['net_price_sell'] > 0 or !$request['gross_price_sell'] > 0) {
            return redirect()->route('medicals.create')->with('danger', 'Brak ceny sprzedaży netto i brutto. Lek nie został dodany!');
        }

        $this->medicalRepository->update($postData, $id);

        return redirect()->route('medicals.list')->with('success', 'Lek został zaktualizowany!');
    }

    public function changeStatus(int $id)
    {
        $this->medicalRepository->change_status($id);

        return redirect()->back()->with('success', 'Status został zmieniony!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->medicalRepository->delete($id);
        return redirect()->back()->with('success', 'Lek został usunięty!');
    }

    private function checkData(array $request)
    {
        $postData =  $request;

        $vat_buy = Vats::where('id', $request['vat_buy_id'])->first();
        $vat_sell = Vats::where('id', $request['vat_sell_id'])->first();

        $netPriceBuy = 0;
        $grossPriceBuy = 0;
        $netPriceSell = 0;
        $grossPriceSell = 0;

        $vatBuyDivisor  = 1 + ((int)$vat_buy->name / 100);
        $vatSellDivisor = 1 + ((int)$vat_sell->name / 100);

        // obliczanie brakujących cen i uzupełnienie w obiekcie request - START

        // ustawiamy null, żeby dalej nie było problemu z ustawianiem - nullujemy :)
        /* if ($request['net_price_buy'] == 0) $postData['net_price_buy'] = NULL;
        if ($request['gross_price_buy'] == 0) $postData['gross_price_buy'] = NULL;
        if ($request['net_price_sell'] == 0) $postData['net_price_sell'] = NULL;
        if ($request['gross_price_sell'] == 0) $postData['gross_price_sell'] = NULL;
         */
        // liczymy brakujące ceny

        // ZAKUP
        if ($request['net_price_buy'] == 0 and $request['gross_price_buy'] > 0) {
            $netPriceBuy = number_format($request['gross_price_buy'] / $vatBuyDivisor, 2);
        }
        if ($request['net_price_buy'] > 0 and $request['gross_price_buy'] == 0) {
            $grossPriceBuy = number_format(($request['net_price_buy'] * $vatBuyDivisor), 2);
        }

        // SPRZEDAŻ
        if ($request['net_price_sell'] == 0 and $request['gross_price_sell'] > 0) {
            $netPriceSell = number_format(($request['gross_price_sell'] / $vatSellDivisor), 2);
        }
        if ($request['net_price_sell'] > 0 and $request['gross_price_sell'] == 0) {
            $grossPriceSell = number_format(($request['net_price_sell'] * $vatSellDivisor), 2);
        }

        // ustawiamy brakujące ceny, bo wartość początkową miały 0
        if ($netPriceBuy > 0) {
            $postData['net_price_buy'] = $netPriceBuy;
        }
        if ($grossPriceBuy > 0) {
            $postData['gross_price_buy'] = $grossPriceBuy;
        }
        if ($netPriceSell > 0) {
            $postData['net_price_sell'] = $netPriceSell;
        }
        if ($grossPriceSell > 0) {
            $postData['gross_price_sell'] = $grossPriceSell;
        }

        // liczymy marżę
        $netMargin = number_format($postData['net_price_sell'] - $postData['net_price_buy'], 2);
        $grossMargin = number_format($postData['gross_price_sell'] - $postData['gross_price_buy'], 2);

        // ustawiamy marżę
        $postData['net_margin'] = $netMargin;
        $postData['gross_margin'] = $grossMargin;
        // obliczanie brakujących cen i uzupełnienie w obiekcie request - STOP

        return $postData;
    }
}
