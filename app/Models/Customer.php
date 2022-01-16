<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'number_herd',
        'nip',
        'address',
        'email',
        'phone',
        'user_id'
    ];

    // ====> RELATIONS <====
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function visits()
    {
        return $this->belongsTo(Visit::class, 'user_id', 'id');
    }
}
