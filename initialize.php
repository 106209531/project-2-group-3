<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$allowedThemes = ['light', 'dark'];

$currentTheme = $_SESSION['theme'] ?? 'light';
$incomingTheme = null;

if (isset($_POST['theme'])) {
    $incomingTheme = $_POST['theme'];
} elseif (isset($_GET['theme'])) {
    $incomingTheme = $_GET['theme'];
} elseif (!isset($_SESSION['theme']) && isset($_COOKIE['theme'])) {
    $incomingTheme = $_COOKIE['theme'];
}

if ($incomingTheme && in_array($incomingTheme, $allowedThemes, true)) {
    $currentTheme = $_SESSION['theme'] = $incomingTheme;
}

if (!isset($_COOKIE['theme']) || $_COOKIE['theme'] !== $currentTheme) {
    setcookie('theme', $currentTheme, time() + 60 * 60 * 24 * 30, '/');
}

$theme = $currentTheme;

if (isset($_POST['theme']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['return_to'] ?? ($_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'] ?? '/');
    $parts = parse_url($target);
    $path = $parts['path'] ?? '/';
    $query = isset($parts['query']) ? '?' . $parts['query'] : '';
    header('Location: ' . $path . $query);
    exit;
}
