<?php
if($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)){ header('Location: apply.php'); exit; }
include "settings.php";
$conn = db_connect();

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  job_ref VARCHAR(6) NOT NULL,
  first_name VARCHAR(20) NOT NULL,
  last_name VARCHAR(20) NOT NULL,
  dob VARCHAR(10) NOT NULL,
  gender VARCHAR(10) NOT NULL,
  street VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state VARCHAR(3) NOT NULL,
  postcode CHAR(4) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  skill1 VARCHAR(40) NULL,
  skill2 VARCHAR(40) NULL,
  skill3 VARCHAR(40) NULL,
  skill4 VARCHAR(40) NULL,
  skill5 VARCHAR(40) NULL,
  skill6 VARCHAR(40) NULL,
  other_skills TEXT NULL,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(job_ref),
  INDEX(last_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// helpers
function clean($s){ return trim(stripslashes(htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'))); }
$errors = [];

$job_ref    = clean($_POST['job_ref'] ?? '');
$first_name = clean($_POST['first_name'] ?? '');
$last_name  = clean($_POST['last_name'] ?? '');
$dob        = clean($_POST['dob'] ?? '');
$gender     = clean($_POST['gender'] ?? '');
$street     = clean($_POST['street'] ?? '');
$suburb     = clean($_POST['suburb'] ?? '');
$state      = clean($_POST['state'] ?? '');
$postcode   = clean($_POST['postcode'] ?? '');
$email      = clean($_POST['email'] ?? '');
$phone      = clean($_POST['phone'] ?? '');
$skills     = $_POST['skills'] ?? [];
$other      = clean($_POST['other_skills'] ?? '');

// Validation per spec
if(!$job_ref) $errors[] = "Job reference is required.";
if(!preg_match('/^[A-Za-z]{1,20}$/',$first_name)) $errors[] = "First name: max 20 alpha characters.";
if(!preg_match('/^[A-Za-z]{1,20}$/',$last_name))  $errors[] = "Last name: max 20 alpha characters.";
if(!preg_match('/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/',$dob)) $errors[] = "DOB must be dd/mm/yyyy.";
if(!in_array($gender, ['Male','Female','Other'])) $errors[] = "Gender is required.";
if(strlen($street) < 1 || strlen($street) > 40) $errors[] = "Street max 40 chars.";
if(strlen($suburb) < 1 || strlen($suburb) > 40) $errors[] = "Suburb max 40 chars.";
$valid_states = ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'];
if(!in_array($state, $valid_states)) $errors[] = "State invalid.";
if(!preg_match('/^\d{4}$/',$postcode)) $errors[] = "Postcode must be exactly 4 digits.";
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalid.";
if(!preg_match('/^[0-9 ]{8,12}$/',$phone)) $errors[] = "Phone must be 8–12 digits or spaces.";

// postcode matches state
function postcode_matches_state($state, $pc){
  $pc = intval($pc);
  switch($state){
    case 'ACT': return ($pc>=200 && $pc<=299) || ($pc>=2600 && $pc<=2618) || ($pc>=2900 && $pc<=2920);
    case 'NSW': return ($pc>=1000 && $pc<=2599) || ($pc>=2619 && $pc<=2898) || ($pc>=2921 && $pc<=2999);
    case 'VIC': return ($pc>=3000 && $pc<=3999) || ($pc>=8000 && $pc<=8999);
    case 'QLD': return ($pc>=4000 && $pc<=4999) || ($pc>=9000 && $pc<=9999);
    case 'SA':  return ($pc>=5000 && $pc<=5799) || ($pc>=5800 && $pc<=5999);
    case 'WA':  return ($pc>=6000 && $pc<=6797) || ($pc>=6800 && $pc<=6999);
    case 'TAS': return ($pc>=7000 && $pc<=7799) || ($pc>=7800 && $pc<=7999);
    case 'NT':  return ($pc>=800 && $pc<=899)   || ($pc>=900 && $pc<=999);
    default: return false;
  }
}
if(!postcode_matches_state($state, $postcode)) $errors[] = "Postcode does not match chosen state.";

// enhancement requirement: if 'Other skills' typed, ensure at least the checkbox is not empty or vice versa
if(!empty($other) && empty($skills)){ $errors[] = "If 'Other skills' provided, select at least one skill too."; }

if($errors){
  http_response_code(400);
  echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><link rel="stylesheet" href="styles/style.css"><title>EOI Error</title></head><body>';
  echo '<main class="card"><h2>Submission Error</h2><div class="notice"><ul>';
  foreach($errors as $e){ echo '<li>'.htmlspecialchars($e).'</li>'; }
  echo '</ul></div><p><a class="btn" href="apply.php">Back to form</a></p></main></body></html>';
  exit;
}

// Map skills into skill1..skill6
$skillSlots = array_slice(array_map('htmlspecialchars', $skills), 0, 6);
while(count($skillSlots)<6){ $skillSlots[] = null; }

$stmt = $conn->prepare("INSERT INTO eoi (job_ref,first_name,last_name,dob,gender,street,suburb,state,postcode,email,phone,skill1,skill2,skill3,skill4,skill5,skill6,other_skills) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param(
  "ssssssssssssssssss",
  $job_ref,$first_name,$last_name,$dob,$gender,$street,$suburb,$state,$postcode,$email,$phone,
  $skillSlots[0],$skillSlots[1],$skillSlots[2],$skillSlots[3],$skillSlots[4],$skillSlots[5],$other
);
$stmt->execute();
$id = $stmt->insert_id;
$stmt->close();

echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><link rel="stylesheet" href="styles/style.css"><title>EOI Submitted</title></head><body>';
echo '<main class="card"><h2>Thank you!</h2><p>Your Expression of Interest was received.</p>';
echo '<p><strong>EOI number:</strong> '.htmlspecialchars($id).'</p>';
echo '<p><a class="btn" href="index.php">Home</a> <a class="btn" href="jobs.php">View Jobs</a></p></main></body></html>';
