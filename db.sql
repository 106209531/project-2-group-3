-- COS10026 Project Part 2 SQL schema
CREATE DATABASE IF NOT EXISTS cos10026_project2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cos10026_project2;

CREATE TABLE IF NOT EXISTS jobs (
  job_ref VARCHAR(6) PRIMARY KEY,
  title VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  salary VARCHAR(40),
  location VARCHAR(80),
  posted DATE NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eoi (
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
  skill1 VARCHAR(40), skill2 VARCHAR(40), skill3 VARCHAR(40),
  skill4 VARCHAR(40), skill5 VARCHAR(40), skill6 VARCHAR(40),
  other_skills TEXT,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(job_ref), INDEX(last_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS managers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  pass_hash VARCHAR(255) NOT NULL,
  failed_attempts INT NOT NULL DEFAULT 0,
  locked_until DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO jobs(job_ref,title,description,salary,location,posted) VALUES
('DEV001','Junior PHP Developer','Assist with building and maintaining PHP websites, write tests, collaborate with seniors.','$65k–$75k','Hanoi/VIC Hybrid', CURDATE()),
('UX002','Accessibility UX Intern','Help audit pages for accessibility, write alt text, test keyboard navigation.','$25/h','Remote',CURDATE()),
('OPS003','Linux Sysadmin','Maintain LAMP stack servers, backups, security patching.','$80k–$95k','Onsite',CURDATE());
