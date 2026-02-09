<?php
declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function showLogin(): void
    {
        view('auth/login');
    }
    
    public function login(): void
    {
        if (!csrf_verify()) {
            die('Invalid CSRF token');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            // Login succesvol
            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role
            ];
            
            // Redirect based on role
            if ($user->role === 'admin') {
                redirect('/admin');
            } else {
                redirect('/dashboard');
            }
        } else {
            // Login mislukt
            view('auth/login', ['error' => 'Ongeldige inloggegevens']);
        }
    }

    public function showRegister(): void
    {
        view('auth/register');
    }

    public function register(): void
    {
        if (!csrf_verify()) {
            die('Invalid CSRF token');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Simpele validatie
        if (empty($username) || empty($email) || empty($password)) {
            view('auth/register', ['error' => 'Alle velden zijn verplicht']);
            return;
        }

        // Check of user al bestaat
        if ($this->userRepo->findByEmail($email)) {
             view('auth/register', ['error' => 'Email is al in gebruik']);
             return;
        }

        // Maak user aan
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($this->userRepo->create($username, $email, $hash)) {
            redirect('/login');
        } else {
            view('auth/register', ['error' => 'Registratie mislukt']);
        }
    }

    public function logout(): void
    {
        session_destroy();
        redirect('/login');
    }
}
