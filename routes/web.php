<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserProfileForm;

Route::get('/', UserProfileForm::class);