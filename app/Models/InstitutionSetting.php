<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionSetting extends Model
{
     protected $fillable = [
        'institution_name',
        'contact_email',
        'contact_phone',
        'address',
        'website',
    ];
}
