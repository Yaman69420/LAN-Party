<?php
declare(strict_types=1);

namespace App\Controllers\Auth;

/**
 * AuthController
 * Behandelt inloggen, registreren en uitloggen
 */
class AuthController
{
    public function showLogin(): void
    {
        view('auth/login');
    }
    
    public function login(): void
    {
        // 1. Validatie
        // 2. Controleren gebruikersnaam/wachtwoord
        // 3. Sessie starten
        // 4. Redirect naar dashboard
    }

    public function showRegister(): void
    {
        view('auth/register');
    }

    public function register(): void
    {
        // 1. Validatie
        // 2. User aanmaken in database
        // 3. Redirect naar login
    }

    public function logout(): void
    {
        session_destroy();
        redirect('/login');
    }
}
