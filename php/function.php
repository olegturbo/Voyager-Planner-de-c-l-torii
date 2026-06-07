<?php

if (!defined('DATA_PATH')) {
    define('DATA_PATH', __DIR__ . '/../data/');
}

// ─────────────────────────────────────────────
// Sanitizare input
// ─────────────────────────────────────────────
function sanitize(string $str): string
{
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

// ─────────────────────────────────────────────
// Validare email
// ─────────────────────────────────────────────
function validateEmail(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

// ─────────────────────────────────────────────
// Mesaje contact
// ─────────────────────────────────────────────
function saveContactMessage(array $msg): void
{
    $file = DATA_PATH . 'messages.json';

    $existing = file_exists($file)
        ? (json_decode(file_get_contents($file), true) ?? [])
        : [];

    $msg['id'] = uniqid('msg_', true);
    $msg['date'] = date('c');

    $existing[] = $msg;

    file_put_contents(
        $file,
        json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

// ─────────────────────────────────────────────
// Dashboard Items
// ─────────────────────────────────────────────
function getItems(): array
{
    $file = DATA_PATH . 'items.json';

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
        return [];
    }

    $data = json_decode(file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function saveItems(array $items): void
{
    $file = DATA_PATH . 'items.json';

    file_put_contents(
        $file,
        json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

// ─────────────────────────────────────────────
// JSON Response
// ─────────────────────────────────────────────
function jsonResponse(bool $success, string $message, array $data = []): never
{
    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ]);

    exit;
}
