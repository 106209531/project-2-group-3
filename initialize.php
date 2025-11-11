<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// receive theme from URL and save it into session
if (isset($_GET['theme'])) {
    $theme = ($_GET['theme'] === 'dark') ? 'dark' : 'light';
    $_SESSION['theme'] = $theme;
}

// for header.inc
$currentTheme = $_SESSION['theme'] ?? 'light';
?>
