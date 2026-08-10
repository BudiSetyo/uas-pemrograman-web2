<?php

function view($view, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../app/views/' . str_replace('.', '/', $view) . '.php';
    if (!file_exists($viewPath)) {
        throw new Exception("View '$view' not found");
    }
    require $viewPath;
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

function error($key)
{
    return $_SESSION['errors'][$key] ?? '';
}

function setErrors($errors)
{
    $_SESSION['errors'] = $errors;
}

function setOld($data)
{
    $_SESSION['old'] = $data;
}

function clearFlash()
{
    unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);
}

function setSuccess($message)
{
    $_SESSION['success'] = $message;
}

function success()
{
    return $_SESSION['success'] ?? '';
}

function auth()
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function generateSlug($string)
{
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function truncate($text, $length = 150)
{
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}