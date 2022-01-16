<?php

namespace App\Imports;

use App\Models\Medical;
use App\Models\UnitMeasure;
use App\Models\Vats;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
//use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MedicalsImport implements ToCollection, WithStartRow
{
    /**
     * Prawidłowe dane - wszystkie dane w wierszu są prawidłowe, także wyliczona marża nie jest na minus
     */
    private $all_rows = 0;
    /**
     * Nieprawidłowe dane w wierszu - brak wartości netto, brutto zakup, brutto sprzedaż
     */
    private $fail_rows = 0;
    /**
     * Marża jest na minus
     */
    private $fail_margin_rows = 0;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 4;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'name';
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    //public function model(array $row)
    public function collection(Collection $rows)
    {
        $all_data = array();
        foreach ($rows as $row) {
            ++$this->all_rows;

            if (empty($row[0]) || !is_numeric($row[1]) || !is_numeric($row[2]) || !is_numeric($row[3])) {
                ++$this->fail_rows;
                continue;
            }

            $name = $row[0];
            $net_price_buy = (float)number_format(str_replace(',', '.', $row[1]), 2, '.', '');
            $gross_price_buy = (float)number_format(str_replace(',', '.', $row[2]), 2, '.', '');
            $gross_price_sell = (float)number_format(str_replace(',', '.', $row[3]), 2, '.', '');
            $net_price_sell = (float)number_format($gross_price_sell / 1.08, 2, '.', '');
            $net_margin = (float)number_format($net_price_sell - $net_price_buy, 2, '.', '');
            $gross_margin = (float)number_format($gross_price_sell - $gross_price_buy, 2, '.', '');

            if ($net_margin < 0 || $gross_margin < 0) {
                ++$this->fail_rows;
                ++$this->fail_margin_rows;

                continue;
            }

            // Unit measure
            $unit_measure_id = 2;
            $unit_measure = UnitMeasure::where('short_name', $row[6])->first();
            if ($unit_measure) {
                $unit_measure_id = $unit_measure->id;
            }

            // Vat sell and buy
            $vat_id = 1;
            $vat = Vats::where('name', $row[7])->first();
            if ($vat) {
                $vat_id = $vat->id;
            }

            // Active
            $active = ($row[8] == "1" ? 1 : 0);

            $data = [
                'name' => $name,
                'vat_buy_id' => $vat_id,
                'vat_sell_id' => $vat_id,
                'net_price_buy' => $net_price_buy,
                'gross_price_buy' => $gross_price_buy,
                'net_price_sell' => $net_price_sell,
                'gross_price_sell' => $gross_price_sell,
                'net_margin' => $net_margin,
                'gross_margin' => $gross_margin,
                'unit_measure_id' => $unit_measure_id,
                'active' => $active
            ];

            // Method 1 - fastest
            $all_data[] = $data;

            // Method 2
            /* Medical::updateOrCreate($data, ['name' => $name]); */


            // Method 3
            /* $medical = Medical::where('name', $name)->first();

            if ($medical) {

                $medical->vat_buy_id = $vat_id;
                $medical->vat_sell_id = $vat_id;
                $medical->net_price_buy = $net_price_buy;
                $medical->gross_price_buy = $gross_price_buy;
                $medical->net_price_sell = $net_price_sell;
                $medical->gross_price_sell = $gross_price_sell;
                $medical->net_margin = $net_margin;
                $medical->gross_margin = $gross_margin;
                $medical->unit_measure_id = $unit_measure_id;
                $medical->active = $active;

                $medical->save();
            } else {
                $model = [
                    'name' => $name,
                    'vat_buy_id' => $vat_id,
                    'vat_sell_id' => $vat_id,
                    'net_price_buy' => $net_price_buy,
                    'gross_price_buy' => $gross_price_buy,
                    'net_price_sell' => $net_price_sell,
                    'gross_price_sell' => $gross_price_sell,
                    'net_margin' => $net_margin,
                    'gross_margin' => $gross_margin,
                    'unit_measure_id' => $unit_measure_id,
                    'active' => $active
                ];
                Medical::create($model);
            } */
        }

        // Method 1 - fastest
        Medical::upsert(
            $all_data,
            ['name'],
            [
                'vat_buy_id',
                'vat_sell_id',
                'net_price_buy',
                'gross_price_buy',
                'net_price_sell',
                'gross_price_sell',
                'net_margin',
                'gross_margin',
                'unit_measure_id',
                'active'
            ]
        );
    }

    public function getAllRowsCount(): int
    {
        return $this->all_rows;
    }

    public function getFailRowsCount(): int
    {
        return $this->fail_rows;
    }

    public function getFailMarginRowsCount(): int
    {
        return $this->fail_margin_rows;
    }
}
