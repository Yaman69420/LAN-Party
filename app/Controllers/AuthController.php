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
        // CSRF beveiliging (deze laten we staan zoals hij was)
        if (function_exists('csrf_verify') && !csrf_verify()) {
            die('Invalid CSRF token');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Haal gebruiker op (Dit komt terug als Object door onze fix in UserRepository)
        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->password_hash)) {
            // Veiligheid: Regenereer sessie ID bij inloggen
            session_regenerate_id(true);

            // --- DE OPLOSSING ---
            // OUDE CODE (Fout):
            // $_SESSION['user'] = ['id' => $user->id, 'username' => $user->username, 'role' => $user->role];

            // NIEUWE CODE (Goed):
            // We zetten het hele object om naar een array.
            // Hierdoor zit 'profile_image', 'first_name', etc. er automatisch bij!
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