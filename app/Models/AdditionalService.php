<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'net_price',
        'gross_price',
        'vat_id',
        'set_price_in_add',
        'active',
        'description'
    ];

    // ====> RELATIONS <====
    public function vat()
    {
        return $this->belongsTo(Vats::class, 'vat_id', 'id');
    }
}
