<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle theme toggle
if (isset($_GET['theme'])) {
    $theme = ($_GET['theme'] === 'dark') ? 'dark' : 'light';
    $_SESSION['theme'] = $theme;
}

// Determine current theme (default = light)
$currentTheme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Swinburne Careers"; ?></title>
  <link rel="stylesheet" href="styles/style.css" />
  <meta name="description" content="COS10026 Project Part 2 — Server-side & Dynamic Web Programming demo site." />
</head>

<!-- Apply the theme class to body -->
<body class="<?php echo $currentTheme; ?>">

<header role="banner" class="site-header">
  <div class="header-top">
    <h1 class="brand">Swin Careers</h1>
    <div class="theme-switcher">
      <a href="?theme=light">Light</a> |
      <a href="?theme=dark">Dark</a>
    </div>
  </div>

  <a class="skip-link" href="#main">Skip to content</a>

  <?php include __DIR__ . "/nav.inc"; ?>
</header>

<main id="main" role="main" tabindex="-1">
