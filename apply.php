<?php
$page_title="Apply";
include "header.inc";
include "settings.php";
$conn = db_connect();
$jobs = mysqli_query($conn, "SELECT job_ref, title FROM jobs ORDER BY job_ref ASC");
$selected = isset($_GET['job_ref']) ? $_GET['job_ref'] : "";
?>
<article class="card">
  <h2>Expression of Interest</h2>
  <form action="process_eoi.php" method="post" novalidate="novalidate" aria-describedby="formhelp">
    <fieldset>
      <legend>Position</legend>
      <label for="job_ref">Job reference</label>
      <select id="job_ref" name="job_ref" required>
        <option value="">-- choose --</option>
        <?php while($j = mysqli_fetch_assoc($jobs)): $jr = htmlspecialchars($j['job_ref']); ?>
          <option value="<?= $jr ?>" <?= $selected===$j['job_ref']?'selected':''; ?>><?= $jr ?> — <?= htmlspecialchars($j['title']) ?></option>
        <?php endwhile; ?>
      </select>
    </fieldset>
    <fieldset>
      <legend>Applicant</legend>
      <div class="grid">
        <label>First name<input type="text" name="first_name" maxlength="20" required></label>
        <label>Last name<input type="text" name="last_name" maxlength="20" required></label>
        <label>Date of birth<input type="text" name="dob" placeholder="dd/mm/yyyy" required></label>
        <fieldset>
          <legend>Gender</legend>
          <label><input type="radio" name="gender" value="Male" required>Male</label>
          <label><input type="radio" name="gender" value="Female">Female</label>
          <label><input type="radio" name="gender" value="Other">Other</label>
        </fieldset>
      </div>
    </fieldset>
    <fieldset>
      <legend>Contact</legend>
      <label>Street address<input type="text" name="street" maxlength="40" required></label>
      <label>Suburb/Town<input type="text" name="suburb" maxlength="40" required></label>
      <label>State
        <select name="state" required>
          <option value="">-- choose --</option>
          <option>VIC</option><option>NSW</option><option>QLD</option><option>NT</option>
          <option>WA</option><option>SA</option><option>TAS</option><option>ACT</option>
        </select>
      </label>
      <label>Postcode<input type="text" name="postcode" pattern="\d{4}" inputmode="numeric" required></label>
      <label>Email<input type="email" name="email" required></label>
      <label>Phone<input type="text" name="phone" placeholder="digits or spaces" required></label>
    </fieldset>
    <fieldset>
      <legend>Skills</legend>
      <label><input type="checkbox" name="skills[]" value="HTML">HTML</label>
      <label><input type="checkbox" name="skills[]" value="CSS">CSS</label>
      <label><input type="checkbox" name="skills[]" value="JavaScript">JavaScript</label>
      <label><input type="checkbox" name="skills[]" value="PHP">PHP</label>
      <label><input type="checkbox" name="skills[]" value="MySQL">MySQL</label>
      <label><input type="checkbox" name="skills[]" value="Accessibility">Accessibility</label>
      <label>Other skills<textarea name="other_skills" rows="3" placeholder="Mention other relevant skills"></textarea></label>
    </fieldset>
    <div class="btn-row">
      <button type="submit">Submit EOI</button>
      <a class="btn" href="jobs.php">Back to Jobs</a>
    </div>
    <p id="formhelp" class="small muted">This form disables HTML5 validation per unit spec; server-side checks will handle format and friendly errors.</p>
  </form>
</article>
<?php include "footer.inc"; ?>
