<?php
$page_title="Manage EOIs";
include "header.inc";
include "settings.php";
$conn = db_connect();

if(!isset($_SESSION['manager_id'])){
  echo '<article class="card"><p class="notice">Please <a href="login.php" class="btn">login</a> to access manager tools.</p></article>';
  include "footer.inc"; exit;
}

// Sorting
$validSort = ['EOInumber','job_ref','last_name','created_at','status'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'],$validSort) ? $_GET['sort'] : 'created_at';

// Actions
$actionMsg = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['delete_by_job'])){
    $jr = trim($_POST['job_ref_del'] ?? '');
    $stmt = $conn->prepare("DELETE FROM eoi WHERE job_ref=?");
    $stmt->bind_param("s",$jr); $stmt->execute(); $stmt->close();
    $actionMsg = "Deleted EOIs with job ref ".htmlspecialchars($jr);
  }
  if(isset($_POST['update_status'])){
    $id = (int)($_POST['eoi_id'] ?? 0);
    $st = $_POST['status'] ?? 'New';
    if(in_array($st,['New','Current','Final'])){
      $stmt = $conn->prepare("UPDATE eoi SET status=? WHERE EOInumber=?");
      $stmt->bind_param("si",$st,$id); $stmt->execute(); $stmt->close();
      $actionMsg = "Updated EOI #".htmlspecialchars($id)." to ".htmlspecialchars($st);
    }
  }
}

$filter_sql = "1=1";
$params = []; $types = "";
if(isset($_GET['filter_job']) && $_GET['filter_job']!==""){
  $filter_sql .= " AND job_ref=?"; $params[] = $_GET['filter_job']; $types.="s";
}
if(isset($_GET['first_name']) && $_GET['first_name']!==""){
  $filter_sql .= " AND first_name LIKE ?"; $params[] = $_GET['first_name']."%"; $types.="s";
}
if(isset($_GET['last_name']) && $_GET['last_name']!==""){
  $filter_sql .= " AND last_name LIKE ?"; $params[] = $_GET['last_name']."%"; $types.="s";
}

$sql = "SELECT * FROM eoi WHERE $filter_sql ORDER BY $sort DESC";
$stmt = $conn->prepare($sql);
if($params){ $stmt->bind_param($types, ...$params); }
$stmt->execute();
$res = $stmt->get_result();
?>
<article class="card">
  <div class="btn-row" style="justify-content: space-between;">
    <h2>EOI Manager</h2>
    <div class="small muted">Logged in as <?= htmlspecialchars($_SESSION['username']??'') ?> | <a class="btn" href="logout.php">Logout</a></div>
  </div>
  <?php if($actionMsg): ?><p class="notice"><?= $actionMsg ?></p><?php endif; ?>

  <form method="get" class="grid" novalidate="novalidate">
    <fieldset>
      <legend>Filter</legend>
      <label>Job Ref <input name="filter_job" value="<?= htmlspecialchars($_GET['filter_job'] ?? '') ?>"></label>
      <label>First name <input name="first_name" value="<?= htmlspecialchars($_GET['first_name'] ?? '') ?>"></label>
      <label>Last name <input name="last_name" value="<?= htmlspecialchars($_GET['last_name'] ?? '') ?>"></label>
      <label>Sort by
        <select name="sort">
          <?php foreach($validSort as $v): ?>
            <option value="<?= $v ?>" <?= $v===$sort?'selected':''; ?>><?= $v ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <button type="submit">Apply</button>
      <a class="btn" href="manage.php">Reset</a>
    </fieldset>
    <fieldset>
      <legend>Admin actions</legend>
      <label>Delete by Job Ref
        <input name="job_ref_del" form="deleteForm">
      </label>
      <form id="deleteForm" method="post">
        <input type="hidden" name="delete_by_job" value="1">
        <button type="submit">Delete</button>
      </form>
      <hr>
      <form method="post" class="btn-row">
        <label>EOI # <input name="eoi_id" type="number" min="1" required style="max-width: 8rem"></label>
        <label>Status
          <select name="status">
            <option>New</option><option>Current</option><option>Final</option>
          </select>
        </label>
        <input type="hidden" name="update_status" value="1">
        <button type="submit">Update</button>
      </form>
    </fieldset>
  </form>

  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>EOI#</th><th>Job</th><th>Name</th><th>DOB</th><th>State</th><th>Postcode</th><th>Email</th><th>Phone</th><th>Status</th><th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= $row['EOInumber'] ?></td>
            <td><?= htmlspecialchars($row['job_ref']) ?></td>
            <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['dob']) ?></td>
            <td><?= htmlspecialchars($row['state']) ?></td>
            <td><?= htmlspecialchars($row['postcode']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
        <?php endwhile; $stmt->close(); ?>
      </tbody>
    </table>
  </div>
</article>
<?php include "footer.inc"; ?>
