<?php

namespace App\Models\Addresses;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'receiver',
        'line_1',
        'line_2',
        'country',
        'phone',
        'phone_2',
        'email',
        'postcode',
        'addressable_id',
        'addressable_type',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
