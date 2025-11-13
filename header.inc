<?php
require_once __DIR__ . '/initialize.php';
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo htmlspecialchars($theme); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Swinburne Careers"; ?></title>
  <link rel="stylesheet" href="styles/style.css?v=<?php echo filemtime(__DIR__ . '/styles/style.css'); ?>" />
  <meta name="description" content="COS10026 Project Part 2 — Server-side & Dynamic Web Programming demo site." />
</head>
<body class="<?php echo htmlspecialchars($theme); ?>">
<?php
$currentPath = $_SERVER['REQUEST_URI'] ?? ($_SERVER['PHP_SELF'] ?? '');
?>
<header role="banner" class="site-header">
  <a class="skip-link" href="#main">Skip to content</a>
  <h1 class="brand">Swin Careers</h1>
  <form class="theme-switcher" method="post" action="<?php echo htmlspecialchars($currentPath); ?>">
    <input type="hidden" name="return_to" value="<?php echo htmlspecialchars($currentPath); ?>" />
    <button
      class="theme-btn light"
      type="submit"
      name="theme"
      value="light"
      aria-pressed="<?php echo $theme === 'light' ? 'true' : 'false'; ?>">
      <span class="icon" aria-hidden="true"></span>
      <span class="label">Light</span>
    </button>
    <button
      class="theme-btn dark"
      type="submit"
      name="theme"
      value="dark"
      aria-pressed="<?php echo $theme === 'dark' ? 'true' : 'false'; ?>">
      <span class="icon" aria-hidden="true"></span>
      <span class="label">Dark</span>
    </button>
  </form>

  <?php include __DIR__ . "/nav.inc"; ?>
</header>
<main id="main" role="main" tabindex="-1">
