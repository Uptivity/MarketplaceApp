<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'contact_name',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country',
        'business_license_number',
        'incorporation_details',
    ];

    // 👇 THIS must be inside the class
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
