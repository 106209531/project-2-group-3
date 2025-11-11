<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Swinburne Careers"; ?></title>
  <link rel="stylesheet" href="style.css" />
</head>

<body class="<?php echo htmlspecialchars($currentTheme); ?>">

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
