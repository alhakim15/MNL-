<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;

class Login extends Page
{
    protected static string $view = 'auth.login'; // Blade kamu

    protected static bool $shouldRegisterNavigation = false; // supaya tidak muncul di sidebar
}
