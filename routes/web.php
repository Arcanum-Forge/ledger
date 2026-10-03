<?php

use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::landing')->name('landing');

Route::middleware('auth')->group(function () {
    // Auth
    Route::post('/logout', LogoutController::class)->name('logout');

    // Dashboard
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('/kingdoms', 'pages::kingdoms.index')->name('kingdoms.index');
    Route::livewire('/regions', 'pages::regions.index')->name('regions.index');
    Route::livewire('/rulers', 'pages::rulers.index')->name('rulers.index');
    Route::livewire('/factions', 'pages::factions.index')->name('factions.index');
    Route::livewire('/leaders', 'pages::leaders.index')->name('leaders.index');
    Route::livewire('/monsters', 'pages::monsters.index')->name('monsters.index');
    Route::livewire('/authors', 'pages::authors.index')->name('authors.index');
    Route::livewire('/records', 'pages::records.index')->name('records.index');
    Route::livewire('/threat-reports', 'pages::threat-reports.index')->name('threat-reports.index');
    Route::livewire('/users', 'pages::users.index')->name('users.index');
});

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
});

