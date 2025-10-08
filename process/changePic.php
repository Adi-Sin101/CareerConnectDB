
<?php
include "../includes/session.php";
include "../includes/conn.php";
session_start();

function getUniqueFilename($originalName, $prefix = '') {
  $ext = pathinfo($originalName, PATHINFO_EXTENSION);
  $unique = uniqid($prefix, true);
  return $unique . '.' . $ext;
}

$uploadDir = '../assets/images/';
$error = '';
$success = false;

if (isset($_POST['myPic']) && isset($_SESSION['id_user'])) {
  $id_user = $_SESSION['id_user'];
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    $filename = getUniqueFilename($_FILES['picture']['name'], 'user_');
    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir . $filename)) {
      $sql = "UPDATE users SET profile_pic = '$filename' WHERE id_user = '$id_user'";
      if ($conn->query($sql)) {
        $success = true;
      } else {
        $error = 'Database error: ' . $conn->error;
      }
    } else {
      $error = 'Failed to move uploaded file.';
    }
  } else {
    $error = 'No file uploaded or upload error.';
  }
}
elseif (isset($_POST['companyPic']) && isset($_SESSION['id_company'])) {
  $id_company = $_SESSION['id_company'];
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    $filename = getUniqueFilename($_FILES['picture']['name'], 'company_');
    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir . $filename)) {
      $sql = "UPDATE company SET profile_pic = '$filename' WHERE id_company = '$id_company'";
      if ($conn->query($sql)) {
        $success = true;
      } else {
        $error = 'Database error: ' . $conn->error;
      }
    } else {
      $error = 'Failed to move uploaded file.';
    }
  } else {
    $error = 'No file uploaded or upload error.';
  }
}
elseif (isset($_POST['aPic']) && isset($_SESSION['id_admin'])) {
  $id_admin = $_SESSION['id_admin'];
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    $filename = getUniqueFilename($_FILES['picture']['name'], 'admin_');
    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadDir . $filename)) {
      $sql = "UPDATE admin SET profile_pic = '$filename' WHERE id_admin = '$id_admin'";
      if ($conn->query($sql)) {
        $success = true;
      } else {
        $error = 'Database error: ' . $conn->error;
      }
    } else {
      $error = 'Failed to move uploaded file.';
    }
  } else {
    $error = 'No file uploaded or upload error.';
  }
}
else {
  $error = 'Invalid request or session.';
}


if ($success) {
  header("Location: ../dashboard/editProfile.php?pic=success");
  exit();
} else {
  echo "<h2>Profile Picture Upload Error</h2>";
  echo "<p style='color:red;'>$error</p>";
  echo "<p><a href='../dashboard/editProfile.php'>Back to Edit Profile</a></p>";
  exit();
}
