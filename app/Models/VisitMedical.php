<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitMedical extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visit_id',
        'medical_id',
        'quantity',
        'vat_id',
        'net_price',
        'gross_price'
    ];

    // ====> RELATIONS <====

    public function vat()
    {
        return $this->belongsTo(Vats::class, 'vat_id', 'id');
    }

    public function medical()
    {
        return $this->belongsTo(Medical::class, 'medical_id', 'id');
    }
}
