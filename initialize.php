<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = ($_GET['theme'] === 'dark') ? 'dark' : 'light';
}
$currentTheme = $_SESSION['theme'] ?? 'light';
?>
