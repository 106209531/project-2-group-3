<?php
$currentPath = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '');
$navItems = [
  ['href' => 'index.php', 'label' => 'Home', 'icon' => 'home'],
  ['href' => 'jobs.php', 'label' => 'Jobs', 'icon' => 'briefcase'],
  ['href' => 'apply.php', 'label' => 'Apply', 'icon' => 'send'],
  ['href' => 'about.php', 'label' => 'About', 'icon' => 'info'],
  ['href' => 'enhancements.php', 'label' => 'Enhancements', 'icon' => 'sparkles'],
  ['href' => 'manage.php', 'label' => 'Manager', 'icon' => 'shield']
];
?>
<nav aria-label="Primary" class="site-nav">
  <ul>
    <?php foreach ($navItems as $item):
      $isActive = $currentPath === $item['href'];
      $classes = 'site-nav__link' . ($isActive ? ' is-active' : '');
    ?>
      <li>
        <a class="<?= $classes ?>"
           href="<?= htmlspecialchars($item['href']); ?>"
           aria-current="<?= $isActive ? 'page' : 'false'; ?>">
          <span class="nav-icon nav-icon--<?= $item['icon']; ?>" aria-hidden="true"></span>
          <span><?= htmlspecialchars($item['label']); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>
