<?php

/**
 * VOYAGER — php/save_data.php
 * AJAX endpoint: handles POST requests for data persistence.
 * Actions: save_destination, delete_destination, save_itinerary,
 *          delete_itinerary, save_contact
 */
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.');
}

$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = sanitize($input['action'] ?? '');

switch ($action) {

    // ── Save / Update destination ────────────
    case 'save_destination':
        requireLogin();
        $city    = sanitize($input['city']    ?? '');
        $country = sanitize($input['country'] ?? '');
        $tag     = sanitize($input['tag']     ?? 'Europa');
        $days    = (int)($input['days']       ?? 1);
        $budget  = sanitize($input['budget']  ?? '');
        $desc    = sanitize($input['desc']    ?? '');
        $emoji   = sanitize($input['emoji']   ?? '🌍');
        $rating  = max(1, min(5, (int)($input['rating'] ?? 4)));
        $id      = sanitize($input['id']      ?? '');

        if (!$city || !$country) {
            jsonResponse(false, 'Câmpuri obligatorii lipsă.');
        }

        $data = compact('city', 'country', 'tag', 'days', 'budget', 'desc', 'emoji', 'rating');

        if ($id) {
            $ok = updateItem($id, $data);
            jsonResponse($ok, $ok ? 'Actualizat cu succes.' : 'ID invalid.');
        } else {
            $item = addItem($data);
            jsonResponse(true, 'Destinație adăugată.', ['item' => $item]);
        }

        // ── Delete destination ───────────────────
    case 'delete_destination':
        requireLogin();
        $id = sanitize($input['id'] ?? '');
        if (!$id) jsonResponse(false, 'ID lipsă.');
        $ok = deleteItem($id);
        jsonResponse($ok, $ok ? 'Destinație ștearsă.' : 'ID invalid.');

        // ── Save contact message ─────────────────
    case 'save_contact':
        $name    = sanitize($input['name']    ?? '');
        $email   = sanitize($input['email']   ?? '');
        $subject = sanitize($input['subject'] ?? '');
        $message = sanitize($input['message'] ?? '');

        if (!$name || !$email || !$message) {
            jsonResponse(false, 'Câmpuri obligatorii lipsă.');
        }
        if (!validateEmail($email)) {
            jsonResponse(false, 'Email invalid.');
        }

        saveContactMessage(compact('name', 'email', 'subject', 'message'));
        jsonResponse(true, 'Mesaj salvat cu succes.');

        // ── Default ──────────────────────────────
    default:
        jsonResponse(false, 'Acțiune necunoscută: ' . $action);
}
