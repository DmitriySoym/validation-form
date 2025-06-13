<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'birth_date',
        'email',
        'phones',
        'marital_status',
        'about_me',
        'files',
    ];

    protected $casts = [
        'phones' => 'array',
        'files' => 'array',
        'birth_date' => 'date',
    ];
}
