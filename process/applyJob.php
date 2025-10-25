<?php
session_start();
include "../includes/conn.php";

if (isset($_GET['id'])) {
  if (!isset($_SESSION['email'])) {
    header("location: ../signin.php");
    exit();
  } else {
    $id_jobpost = $_GET['id'];
    $id_company = $_GET['cid'];
    $id_user = $_SESSION['user_id'];
    $createdat = date("Y-m-d");

    // Check if resume exists
    $query = $conn->query("SELECT resume FROM users WHERE id_user = '$id_user'");
    $resume = $query->fetch_assoc();

    if (empty($resume['resume'])) {
      $_SESSION['message'] = "Please upload your resume first!";
      $_SESSION['messagetype'] = 'error';
      header("location: ../dashboard/myresume.php");
      exit();
    } else {
      // Insert into applied_jobposts table
      $sql = "INSERT INTO applied_jobposts (id_jobpost, id_user, id_company, createdat) 
              VALUES ('$id_jobpost', '$id_user', '$id_company', '$createdat')";
      if ($conn->query($sql)) {
        $_SESSION['message'] = "Application submitted successfully!";
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = "Error submitting application. Please try again.";
        $_SESSION['messagetype'] = 'error';
      }
    }

    // === Report Generation ===
    if (file_exists('../tcpdf/tcpdf.php')) {
      require_once('../tcpdf/tcpdf.php');

      // Create PDF
      $pdf = new TCPDF();
      $pdf->AddPage();
      $pdf->SetFont('helvetica', 'B', 16);
      $pdf->Cell(0, 10, 'Job Application Report', 0, 1, 'C');
      $pdf->SetFont('helvetica', '', 12);

      // Job details
      $job = $conn->query("SELECT * FROM job_post WHERE id_jobpost='$id_jobpost'")->fetch_assoc();

      // Company details
      $company = $conn->query("SELECT * FROM company WHERE id_company='$id_company'")->fetch_assoc();

      // State info
      $state = $conn->query("SELECT name FROM states WHERE id='{$job['state_id']}'")->fetch_assoc();

      // City info
      $city = $conn->query("SELECT name FROM districts_or_cities WHERE id='{$job['city_id']}'")->fetch_assoc();

      // Education info
      $education = $conn->query("SELECT name FROM education WHERE id='{$job['edu_qualification']}'")->fetch_assoc();

      // Job type
      $jobtype = $conn->query("SELECT type FROM job_type WHERE id='{$job['job_status']}'")->fetch_assoc();

      // Industry info
      $industry = $conn->query("SELECT name FROM industry WHERE id='{$job['industry_id']}'")->fetch_assoc();

      // User info
      $user = $conn->query("SELECT * FROM users WHERE id_user='$id_user'")->fetch_assoc();

      // Report content
      $content = "
Job Details
-------------------------
Job Title: {$job['jobtitle']}
Industry: {$industry['name']}
Salary (Tk.): {$job['minimumsalary']} - {$job['maximumsalary']}
Company: {$company['companyname']}
Division: {$state['name']}
City: {$city['name']}
Education Required: {$education['name']}
Job Type: {$jobtype['type']}

------------------------------------------

Applicant Details
-------------------------
Full Name: {$user['fullname']}
Email: {$user['email']}
Gender: {$user['gender']}
Contact: {$user['contactno']}
DOB: {$user['dob']}
Address: {$user['address']}
      ";

      // Add text to PDF
      $pdf->MultiCell(0, 10, $content);
      $pdf->Output('job_application_report.pdf', 'I');
    } else {
      $_SESSION['message'] = "Application submitted successfully!";
    }
  }

  header("location: ../jobDetails.php?key=" . md5($id_jobpost) . "&id=" . $id_jobpost);
}
?>
