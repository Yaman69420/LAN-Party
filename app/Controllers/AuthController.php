<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
        if (!csrf_verify()) die('Invalid CSRF token');

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->password_hash)) {
            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ];
            header("Location: /dashboard");
            exit;
        } else {
            view('auth/login', ['error' => 'Ongeldige inloggegevens']);
        }
    }

    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            header("Location: /dashboard");
            exit;
        }

        // We gebruiken hier NIET de view() helper van je collega als die automatisch de sidebar laadt
        // We laden het bestand puur en alleen:
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function showRegister(): void {
        if (isset($_SESSION['user'])) { header("Location: /dashboard"); exit; }
        view('auth/register');
    }

    public function register(): void {
        if (!csrf_verify()) die('Invalid CSRF token');

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';

        // Basic validation
        if (empty($username) || empty($email) || empty($password)) {
             view('auth/register', ['error' => 'Vul alle verplichte velden in.']);
             return;
        }

        // Check if user already exists
        if ($this->userRepo->findByEmail($email)) {
            view('auth/register', ['error' => 'Dit e-mailadres is al in gebruik.']);
            return;
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Create user
        if ($this->userRepo->create($username, $email, $passwordHash, $firstName, $lastName)) {
            // Auto login or redirect to login
            view('auth/login', ['success' => 'Account aangemaakt! Je kunt nu inloggen.']);
        } else {
            view('auth/register', ['error' => 'Er ging iets mis bij het registreren.']);
        }
    }

    public function logout(): void {
        session_destroy();
        header("Location: /login");
        exit;
    }
}