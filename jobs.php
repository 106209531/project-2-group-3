<?php
$page_title="Jobs";
include "initialize.php";
include "header.inc";
include "settings.php";
$conn = db_connect();

// create jobs table if not exists and seed sample jobs when empty
$create = "CREATE TABLE IF NOT EXISTS jobs (
  job_ref VARCHAR(6) PRIMARY KEY,
  title VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  salary VARCHAR(40) NULL,
  location VARCHAR(80) NULL,
  posted DATE NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

mysqli_query($conn, $create);

$check = mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs");
$count = $check ? (int)mysqli_fetch_assoc($check)['c'] : 0;
if ($count === 0) {
  $seed = $conn->prepare("INSERT INTO jobs(job_ref,title,description,salary,location,posted) VALUES (?,?,?,?,?,CURDATE())");
  $jobs = [
    ["DEV001","Junior PHP Developer","Assist with building and maintaining PHP websites, write tests, collaborate with seniors.","$65k–$75k","Hanoi/VIC Hybrid"],
    ["UX002","Accessibility UX Intern","Help audit pages for accessibility, write alt text, test keyboard navigation.","$25/h","Remote"],
    ["OPS003","Linux Sysadmin","Maintain LAMP stack servers, backups, security patching.","$80k–$95k","Onsite"],
  ];
  foreach ($jobs as $j) { $seed->bind_param("sssss",$j[0],$j[1],$j[2],$j[3],$j[4]); $seed->execute(); }
  $seed->close();
}

$jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY posted DESC, job_ref ASC");
?>
<article class="card">
  <h2>Open Positions</h2>
  <table class="table" aria-describedby="jobhelp">
    <thead><tr><th>Ref</th><th>Title</th><th>Location</th><th>Salary</th><th>Posted</th><th></th></tr></thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($jobs)): ?>
        <tr>
          <td><?= htmlspecialchars($row['job_ref']) ?></td>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><?= htmlspecialchars($row['salary']) ?></td>
          <td><?= htmlspecialchars($row['posted']) ?></td>
          <td><a class="btn" href="apply.php?job_ref=<?= urlencode($row['job_ref']) ?>">Apply</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <p id="jobhelp" class="muted small">Use the Apply button to pre-select the job on the form.</p>
</article>
<?php include "footer.inc"; ?>
