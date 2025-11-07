<?php
$page_title="Manager Login";
include "header.inc";
include "settings.php";
$conn = db_connect();

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u = trim($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';
  $stmt = $conn->prepare("SELECT id, pass_hash, failed_attempts, locked_until FROM managers WHERE username=?");
  $stmt->bind_param("s",$u); $stmt->execute(); $res = $stmt->get_result();
  if($row = $res->fetch_assoc()){
    $now = new DateTime();
    if(!empty($row['locked_until']) && $now < new DateTime($row['locked_until'])){
      $errors[] = "Account locked. Try again later.";
    } else if(password_verify($p, $row['pass_hash'])){
      $_SESSION['manager_id'] = $row['id'];
      $_SESSION['username'] = $u;
      $conn->query("UPDATE managers SET failed_attempts=0, locked_until=NULL WHERE id=".$row['id']);
      header("Location: manage.php"); exit;
    } else {
      $fa = (int)$row['failed_attempts'] + 1;
      $lock = "NULL";
      if($fa >= 3){
        $lockUntil = (new DateTime('+10 minutes'))->format('%Y-%m-%d %H:%M:%S');
        $lock = "'$lockUntil'";
        $fa = 0; // reset after locking
      }
      $conn->query("UPDATE managers SET failed_attempts=$fa, locked_until=$lock WHERE id=".$row['id']);
      $errors[] = "Invalid credentials.";
    }
  } else {
    $errors[] = "Invalid credentials.";
  }
  $stmt->close();
}
?>
<article class="card">
  <h2>Manager Login</h2>
  <?php if($errors): ?><div class="notice"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
  <form method="post" novalidate="novalidate">
    <label>Username<input name="username" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit">Login</button>
    <a class="btn" href="register.php">Register</a>
  </form>
</article>
<?php include "footer.inc"; ?>
