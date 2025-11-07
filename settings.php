<?php
// Update these to your local MySQL (Mercury/XAMPP) credentials
$host = 'localhost';
$user = 'root';
$pwd  = '';        // e.g., 'root' on macOS, '' on XAMPP Windows
$sql_db = 'cos10026_project2';

// Reusable helper for DB connection
function db_connect() {
  global $host, $user, $pwd, $sql_db;
  $conn = @mysqli_connect($host, $user, $pwd);
  if (!$conn) { die('<p class="notice">Database connection failed.</p>'); }
  @mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
  @mysqli_select_db($conn, $sql_db) or die('<p class="notice">Could not select database.</p>');
  return $conn;
}
?>
