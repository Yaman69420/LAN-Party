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
        // CSRF beveiliging
        if (function_exists('csrf_verify') && !csrf_verify()) {
            die('Invalid CSRF token');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepo->findByEmail($email);

        // --- BULLETPROOF FIX ---
        // Haal het wachtwoord veilig op, ongeacht of $user een Array of Object is.
        $hash = '';
        if (is_array($user)) {
            $hash = $user['password_hash'] ?? '';
        } elseif (is_object($user)) {
            $hash = $user->password_hash ?? '';
        }

        // Check of we een gebruiker hebben EN of het wachtwoord klopt met de hash
        if ($user && password_verify($password, $hash)) {
            // Veiligheid: Regenereer sessie ID bij inloggen
            session_regenerate_id(true);

            // Zet alles (array of object) altijd veilig om naar een array voor de sessie
            $_SESSION['user'] = (array) $user;

            header("Location: /dashboard");
            exit;
        } else {
            // Als login faalt
            view('auth/login', ['error' => 'Ongeldige inloggegevens']);
        }
    }

    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            header("Location: /dashboard");
            exit;
        }

        // We laden het bestand direct zoals je vroeg
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function showRegister(): void {
        if (isset($_SESSION['user'])) { header("Location: /dashboard"); exit; }
        view('auth/register');
    }

    public function register(): void {
        if (function_exists('csrf_verify') && !csrf_verify()) {
            die('Invalid CSRF token');
        }

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
        // Let op: zorg dat je UserRepository een create functie heeft die deze 5 argumenten accepteert
        if ($this->userRepo->create($username, $email, $passwordHash, $firstName, $lastName)) {
            view('auth/login', ['success' => 'Account aangemaakt! Je kunt nu inloggen.']);
        } else {
            view('auth/register', ['error' => 'Er ging iets mis bij het registreren.']);
        }
    }

    public function logout(): void {
        // Sessie volledig wissen
        $_SESSION = [];
        session_destroy();
        header("Location: /login");
        exit;
    }
}