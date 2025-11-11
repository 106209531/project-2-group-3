<?php
$page_title="Manager Registration";
include "initialize.php";
include "header.inc";
include "settings.php";
$conn = db_connect();

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS managers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  pass_hash VARCHAR(255) NOT NULL,
  failed_attempts INT NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$errors = []; $done = false;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u = trim($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';
  if(!preg_match('/^[A-Za-z0-9_\.\-]{4,50}$/',$u)) $errors[]="Username 4–50 chars (letters, numbers, underscore, dot, hyphen).";
  if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',$p)) $errors[]="Password min 8 with upper, lower, digit.";
  if(!$errors){
    $stmt = $conn->prepare("SELECT id FROM managers WHERE username=?");
    $stmt->bind_param("s",$u); $stmt->execute(); $stmt->store_result();
    if($stmt->num_rows>0){ $errors[]="Username already exists."; }
    $stmt->close();
  }
  if(!$errors){
    $hash = password_hash($p, PASSWORD_DEFAULT);
    $stmt=$conn->prepare("INSERT INTO managers(username,pass_hash) VALUES(?,?)");
    $stmt->bind_param("ss",$u,$hash); $stmt->execute(); $stmt->close();
    $done = true;
  }
}
?>
<article class="card">
  <h2>Manager Registration</h2>
  <?php if($done): ?>
    <p class="notice">Registered successfully. <a href="login.php" class="btn">Go to Login</a></p>
  <?php else: ?>
  <?php if($errors): ?><div class="notice"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
  <form method="post" novalidate="novalidate">
    <label>Username<input name="username" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit">Create account</button>
  </form>
  <?php endif; ?>
</article>
<?php include "footer.inc"; ?>
