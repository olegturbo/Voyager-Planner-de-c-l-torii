<?php
define('DATA_PATH', __DIR__ . '/../data/');

function getUsers(): array
{
    $file = DATA_PATH . 'users.json';
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function saveUsers(array $users): void
{
    $file = DATA_PATH . 'users.json';
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function findUserByEmail(string $email): ?array
{
    foreach (getUsers() as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function registerUser(string $name, string $email, string $password): array
{
    $users = getUsers();
    $newUser = [
        'id'       => uniqid('usr_', true),
        'name'     => htmlspecialchars($name),
        'email'    => strtolower(trim($email)),
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'joined'   => date('c'),
        'role'     => 'user',
    ];
    $users[] = $newUser;
    saveUsers($users);
    return $newUser;
}

function loginUser(string $email, string $password): ?array
{
    $user = findUserByEmail($email);
    if (!$user) return null;
    if (!password_verify($password, $user['password'])) return null;
    return $user;
}

function setSession(array $user): void
{
    $_SESSION['voy_user_id'] = $user['id'];
    $_SESSION['voy_user_name'] = $user['name'];
    $_SESSION['voy_user_email'] = $user['email'];
}

function getSessionUser(): ?array
{
    if (empty($_SESSION['voy_user_id'])) return null;
    return [
        'id' => $_SESSION['voy_user_id'],
        'name' => $_SESSION['voy_user_name'],
        'email' => $_SESSION['voy_user_email'],
    ];
}

function logoutUser(): void
{
    session_destroy();
    session_start();
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['voy_user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        http_response_code(403);
        header('Location: ../index.php?page=login&err=protected');
        exit;
    }
}
