<?php

define('DATA_PATH', __DIR__ . '/../data/');

// ─── Sanitizare input ───────────────────────
function sanitize(string $str): string
{
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

// ─── Validare email ─────────────────────────
function validateEmail(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

// ─── Salvare mesaj contact în JSON ──────────
function saveContactMessage(array $msg): void
{
    $file = DATA_PATH . 'messages.json';
    $existing = file_exists($file)
        ? (json_decode(file_get_contents($file), true) ?? [])
        : [];
    $msg['id']   = uniqid('msg_', true);
    $msg['date'] = date('c');
    $existing[]  = $msg;
    file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function jsonResponse(bool $success, string $message, array $data = []): never
{
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}
