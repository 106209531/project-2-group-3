<?php
if(session_status() === PHP_SESSION_NONE){ session_start(); }
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Swinburne Careers"; ?></title>
  <link rel="stylesheet" href="styles/style.css" />
  <meta name="description" content="COS10026 Project Part 2 — Server-side & Dynamic Web Programming demo site." />
</head>
<body>
<header role="banner" class="site-header">
  <a class="skip-link" href="#main">Skip to content</a>
  <h1 class="brand">Swin Careers</h1>
  <?php include __DIR__ . "/nav.inc"; ?>
</header>
<main id="main" role="main" tabindex="-1">
