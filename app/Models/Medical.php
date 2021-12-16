<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
    ];

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
