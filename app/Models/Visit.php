<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'customer_id',
        'visit_number',
        'visit_date',
        'pay_type_id'
    ];

    // ====> RELATIONS <====

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function pay_type()
    {
        return $this->belongsTo(PayTypes::class, 'pay_type_id', 'id');
    }

    public function medicals()
    {
        return $this->belongsToMany(VisitMedical::class, 'medical_id');
    }
}
