<?php

/**
 * VOYAGER — php/functions.php
 * General utility functions for the application.
 */

require_once __DIR__ . '/auth.php';

// ─── Sanitization ───────────────────────────
function sanitize(string $str): string
{
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function validateEmail(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

// ─── Items (destinations) CRUD ─────────────
function getItems(): array
{
    $file = DATA_PATH . 'items.json';
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function saveItems(array $items): void
{
    $file = DATA_PATH . 'items.json';
    file_put_contents($file, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function addItem(array $item): array
{
    $items = getItems();
    $item['id']      = uniqid('item_', true);
    $item['created'] = date('c');
    $items[] = $item;
    saveItems($items);
    return $item;
}

function updateItem(string $id, array $data): bool
{
    $items = getItems();
    foreach ($items as &$item) {
        if ($item['id'] === $id) {
            $item = array_merge($item, $data, ['id' => $id]);
            saveItems($items);
            return true;
        }
    }
    return false;
}

function deleteItem(string $id): bool
{
    $items = getItems();
    $filtered = array_filter($items, fn($i) => $i['id'] !== $id);
    if (count($filtered) === count($items)) return false;
    saveItems(array_values($filtered));
    return true;
}

// ─── Contact messages ───────────────────────
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

// ─── Ensure data dir & files exist ──────────
function ensureDataFiles(): void
{
    if (!is_dir(DATA_PATH)) mkdir(DATA_PATH, 0755, true);
    $files = ['users.json', 'items.json', 'messages.json'];
    foreach ($files as $f) {
        $path = DATA_PATH . $f;
        if (!file_exists($path)) file_put_contents($path, '[]');
    }
}

ensureDataFiles();
