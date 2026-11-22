<?php
declare(strict_types=1);

/** Échappement HTML (protection XSS) : à utiliser pour TOUT affichage de donnée. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL d'une action du point d'entrée unique : index.php?action=... */
function route(string $action, array $parameters = []): string
{
    return 'index.php?' . http_build_query(['action' => $action] + $parameters);
}

// ---------------------------------------------------------------------------
// CSRF : un jeton unique par session, vérifié avec hash_equals()
// ---------------------------------------------------------------------------
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_input(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $submitted = null): bool
{
    $submitted = $submitted ?? (string) ($_POST['csrf_token'] ?? '');
    $known = (string) ($_SESSION['csrf_token'] ?? '');

    return $submitted !== '' && $known !== '' && hash_equals($known, $submitted);
}

// ---------------------------------------------------------------------------
// Messages flash + redirection
// ---------------------------------------------------------------------------
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

function redirect_to(string $action, array $parameters = []): never
{
    header('Location: ' . route($action, $parameters));
    exit;
}
