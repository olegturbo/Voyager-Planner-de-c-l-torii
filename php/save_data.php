<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.');
}

$input  = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = sanitize($input['action'] ?? '');

switch ($action) {

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
        break;

    default:
        jsonResponse(false, 'Acțiune necunoscută.');
}
