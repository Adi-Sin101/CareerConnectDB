<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/conn.php";

if (isset($_POST['changeResume'])) {
  $email = mysqli_real_escape_string($conn, $_SESSION['email']);

  // Validate file upload
  if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['message'] = 'File upload error occurred';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../dashboard/myresume.php');
    exit();
  }

  $resume = $_FILES['resume']['name'];
  $fileType = strtolower(pathinfo($resume, PATHINFO_EXTENSION));
  $fileSize = $_FILES['resume']['size'];

  // Validate file type and size
  if ($fileType !== 'pdf') {
    $_SESSION['message'] = 'Only PDF files are allowed';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../dashboard/myresume.php');
    exit();
  }

  if ($fileSize > 5242880) { // 5MB limit
    $_SESSION['message'] = 'File size must be less than 5MB';
    $_SESSION['messagetype'] = 'warning';
    header('location: ../dashboard/myresume.php');
    exit();
  }

  //hash email
  $hash = md5($email);
  $filename = $hash . $resume;  if ($resume != '') {
    // Ensure the resumes directory exists
    $uploadDir = '../assets/resumes/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $uploadDir . $filename)) {
      $sql = "UPDATE users SET resume = '$filename' WHERE email = '$email'";
      if ($conn->query($sql)) {
        $_SESSION['message'] = 'Resume updated successfully';
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = $conn->error;
        $_SESSION['messagetype'] = 'warning';
      }
    } else {
      $_SESSION['message'] = "There was an error, file couldn't be uploaded";
      $_SESSION['messagetype'] = 'warning';
    }
  }
}

header('location: ../dashboard/myresume.php');
exit();
