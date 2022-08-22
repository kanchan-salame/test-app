<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'company_email',
        'company_name',
        'company_website',
        'company_phone',
        'company_address',
        'company_password',
        'photo_path',
        'status',
        'user_id',
    ];
}
