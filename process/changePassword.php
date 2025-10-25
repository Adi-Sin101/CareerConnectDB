<?php
include "../includes/session.php";

if (isset($_POST['myPassword'])) {
  $email = $_SESSION['email'];
  $newPassword = $_POST['newPassword'];

  if (!empty($newPassword)) {
    $password = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update query to change user password
    $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
    if ($conn->query($sql)) {
      $_SESSION['message'] = "Password updated successfully!";
      $_SESSION['messagetype'] = 'success';
    } else {
      $_SESSION['message'] = "Error updating password: " . $conn->error;
      $_SESSION['messagetype'] = 'error';
    }
  } else {
    $_SESSION['message'] = "New password cannot be empty.";
    $_SESSION['messagetype'] = 'error';
  }
}

if (isset($_POST['companyPassword'])) {
  $email = $_SESSION['email'];
  $newPassword = $_POST['newPassword'];

  if (!empty($newPassword)) {
    $password = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update query to change company password
    $sql = "UPDATE company SET password = '$password' WHERE email = '$email'";
    if ($conn->query($sql)) {
      $_SESSION['message'] = "Password updated successfully!";
      $_SESSION['messagetype'] = 'success';
    } else {
      $_SESSION['message'] = "Error updating password: " . $conn->error;
      $_SESSION['messagetype'] = 'error';
    }
  } else {
    $_SESSION['message'] = "New password cannot be empty.";
    $_SESSION['messagetype'] = 'error';
  }
}



header('location: ../dashboard/editProfile.php');
exit();
