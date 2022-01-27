<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\VisitRepositoryInterface;
use App\Models\Visit;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    private VisitRepositoryInterface $visitRepository;

    public function __construct(VisitRepositoryInterface $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visits = $this->visitRepository->all();

        //$visit_calc_details = [];
        $turnover_margin_stats = [];
        foreach ($visits as $visit) {
            //$visit_calc_details = $this->visitRepository->getVisitCalcDetails($visit, $visit_calc_details);
            $turnover_margin_stats = $this->visitRepository->addTurnoverMarginStats($visit, $turnover_margin_stats);
        }

        //$visit_calc_details = $this->visitRepository->addSummaryVisitToCalcDetails($visit_calc_details);
        $turnover_margin_stats_sum = $this->visitRepository->sumTurnoverMarginStats($turnover_margin_stats);

        return response()->json($turnover_margin_stats_sum, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visit $visit)
    {
        //
    }
}
