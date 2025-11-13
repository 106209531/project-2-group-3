<?php
$page_title = "Apply";
include "header.inc";
include "settings.php";
$conn = db_connect();
$jobs = mysqli_query($conn, "SELECT job_ref, title FROM jobs ORDER BY job_ref ASC");

$old_input = $_SESSION['old_input'] ?? [];
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['old_input'], $_SESSION['form_errors']);

$old_input = is_array($old_input) ? $old_input : [];
$errors = is_array($errors) ? $errors : [];

$selected_job = $old_input['job_ref'] ?? ($_GET['job_ref'] ?? "");
$gender_selected = $old_input['gender'] ?? '';
$state_selected = $old_input['state'] ?? '';
$skill_selected = isset($old_input['skills']) && is_array($old_input['skills']) ? $old_input['skills'] : [];
$skill_options = ['HTML','CSS','JavaScript','PHP','MySQL','Accessibility'];
?>
<article class="card">
  <h2>Expression of Interest</h2>
  <?php if ($errors): ?>
    <div class="notice" role="alert">
      <p><strong>Please fix the following:</strong></p>
      <ul>
        <?php foreach ($errors as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form action="process_eoi.php" method="post" novalidate="novalidate" aria-describedby="formhelp">
    <fieldset>
      <legend>Position</legend>
      <label for="job_ref">Job reference</label>
      <select id="job_ref" name="job_ref" required>
        <option value="">-- choose --</option>
        <?php while ($j = mysqli_fetch_assoc($jobs)): $jr = htmlspecialchars($j['job_ref']); ?>
          <option value="<?= $jr ?>" <?= $selected_job === $j['job_ref'] ? 'selected' : ''; ?>>
            <?= $jr ?> - <?= htmlspecialchars($j['title']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </fieldset>
    <fieldset>
      <legend>Applicant</legend>
      <div class="grid">
        <label>First name
          <input type="text" name="first_name" maxlength="20" required value="<?= htmlspecialchars($old_input['first_name'] ?? '') ?>">
        </label>
        <label>Last name
          <input type="text" name="last_name" maxlength="20" required value="<?= htmlspecialchars($old_input['last_name'] ?? '') ?>">
        </label>
        <label>Date of birth
          <input type="text" name="dob" placeholder="dd/mm/yyyy" required value="<?= htmlspecialchars($old_input['dob'] ?? '') ?>">
        </label>
        <fieldset>
          <legend>Gender</legend>
          <label><input type="radio" name="gender" value="Male" required <?= $gender_selected === 'Male' ? 'checked' : ''; ?>>Male</label>
          <label><input type="radio" name="gender" value="Female" <?= $gender_selected === 'Female' ? 'checked' : ''; ?>>Female</label>
          <label><input type="radio" name="gender" value="Other" <?= $gender_selected === 'Other' ? 'checked' : ''; ?>>Other</label>
        </fieldset>
      </div>
    </fieldset>
    <fieldset>
      <legend>Contact</legend>
      <label>Street address
        <input type="text" name="street" maxlength="40" required value="<?= htmlspecialchars($old_input['street'] ?? '') ?>">
      </label>
      <label>Suburb/Town
        <input type="text" name="suburb" maxlength="40" required value="<?= htmlspecialchars($old_input['suburb'] ?? '') ?>">
      </label>
      <label>State
        <select name="state" required>
          <option value="">-- choose --</option>
          <?php foreach (['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'] as $st): ?>
            <option value="<?= $st ?>" <?= $state_selected === $st ? 'selected' : ''; ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Postcode
        <input type="text" name="postcode" pattern="\d{4}" inputmode="numeric" required value="<?= htmlspecialchars($old_input['postcode'] ?? '') ?>">
      </label>
      <label>Email
        <input type="email" name="email" required value="<?= htmlspecialchars($old_input['email'] ?? '') ?>">
      </label>
      <label>Phone
        <input type="text" name="phone" placeholder="digits or spaces" required value="<?= htmlspecialchars($old_input['phone'] ?? '') ?>">
      </label>
    </fieldset>
    <fieldset>
      <legend>Skills</legend>
      <?php foreach ($skill_options as $skill): ?>
        <label><input type="checkbox" name="skills[]" value="<?= $skill ?>" <?= in_array($skill, $skill_selected, true) ? 'checked' : ''; ?>><?= $skill ?></label>
      <?php endforeach; ?>
      <label>Other skills
        <textarea name="other_skills" rows="3" placeholder="Mention other relevant skills"><?= htmlspecialchars($old_input['other_skills'] ?? '') ?></textarea>
      </label>
    </fieldset>
    <div class="btn-row">
      <button type="submit">Submit EOI</button>
      <a class="btn" href="jobs.php">Back to Jobs</a>
    </div>
    <p id="formhelp" class="small muted">This form disables HTML5 validation per unit spec; server-side checks will handle format and friendly errors.</p>
  </form>
</article>
<?php include "footer.inc"; ?>
