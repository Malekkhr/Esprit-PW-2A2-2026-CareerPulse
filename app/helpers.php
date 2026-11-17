<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function route(string $page, array $parameters = []): string
{
    return 'index.php?' . http_build_query(['page' => $page] + $parameters);
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['_csrf_token'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $submitted = (string) ($_POST['_token'] ?? '');
    $known = (string) ($_SESSION['_csrf_token'] ?? '');

    return $submitted !== '' && $known !== '' && hash_equals($known, $submitted);
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function consume_flash(): ?array
{
    $message = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);

    return is_array($message) ? $message : null;
}

function redirect_to(string $page): never
{
    header('Location: ' . route($page));
    exit;
}
