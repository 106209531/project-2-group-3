<?php $page_title="Enhancements"; include "header.inc"; ?>
<article class="card">
  <h2>Enhancements</h2>
  <ol>
    <li><strong>Server-side postcode ⇄ state validation</strong>: AU postcode ranges verified against the chosen state.</li>
    <li><strong>Graceful lockout</strong>: After 3 failed logins, manager account is locked for 10 minutes with audit fields.</li>
    <li><strong>Accessible UI</strong>: Skip link, focus indicators, semantic landmarks, clear labels, keyboard-only friendly navigation, high-contrast palette.</li>
    <li><strong>Jobs table</strong>: Positions stored and rendered dynamically; “Apply” preselects ref via query string.</li>
    <li><strong>Friendly error summaries</strong>: Server validation returns grouped list with persistent link back to form.</li>
  </ol>
  <p class="muted small">Accessibility guidelines (headings, alt text, form labels, keyboard focus, contrast) were applied across the site.</p>
</article>
<?php include "footer.inc"; ?>
