<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Register extends BaseRegister
{
    public static function getLabel(): string
    {
        return 'Register';
    }

    // ✅ FORM register
    public function getForm(string $name): ?Form
    {
        return parent::getForm($name)
            ?->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required()
                    ->minLength(8),
            ]);
    }

    // ✅ Proses simpan user baru ke database
    protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // Set email verified if needed
        ]);
        $assignedRole = 'user'; // Ganti dengan role yang sesuai jika ada
        $user->assignRole($assignedRole); // Assign role jika menggunakan Spatie/Permission
        return $user;
    }

    // ✅ Arahkan ke halaman / setelah register
    protected function redirectTo(): string
    {
        return 'home'; // bisa kamu ganti ke route lain seperti /dashboard jika perlu
    }
}
