<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    // ====> RELATIONS <====
    public function vat_buy()
    {
        return $this->belongsTo(Vats::class, 'vat_buy_id', 'id');
    }

    public function vat_sell()
    {
        return $this->belongsTo(Vats::class, 'vat_sell_id', 'id');
    }

    public function unit_measure()
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_measure_id', 'id');
    }
}
