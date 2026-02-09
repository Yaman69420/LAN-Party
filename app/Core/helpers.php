<?php
declare(strict_types=1);

/**
 * Helper functies
 */

/**
 * Escape output voor veilige weergave in HTML
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Genereer CSRF token
 */
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Genereer hidden CSRF input field
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Valideer CSRF token
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect naar een URL
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Laad een view met data
 */
function view(string $name, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/' . $name . '.php';
}

/**
 * Check of gebruiker is ingelogd
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Check of gebruiker admin is
 */
function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['user']['role'] === 'admin');
}

/**
 * Haal ingelogde user op
 */
function user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Require Login - Redirect als niet ingelogd
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

/**
 * Require Admin - Redirect (of 403) als niet admin
 */
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        die('403 - Geen toegang');
    }
}
