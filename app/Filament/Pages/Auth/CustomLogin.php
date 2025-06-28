<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;

class Login extends BaseLogin
{
    protected static string $view = 'auth.custom-login'; // pakai custom blade

    public function form(Form $form): Form
    {
        return parent::form($form);
    }

    // protected function hasLogo(): bool
    // {
    //     return true; // tampilkan logo kalau ada
    // }
}
