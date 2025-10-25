<?php
include "../includes/session.php";

if (isset($_POST['myPic'])) {
  $id_user = $_SESSION['user_id'];
  $profile_pic = $_FILES['picture']['name'];

  if ($profile_pic != '') {
    // Generate unique filename using user ID and timestamp
    $ext = pathinfo($profile_pic, PATHINFO_EXTENSION);
    $filename = 'user_' . $id_user . '_' . time() . '.' . $ext;

    if (move_uploaded_file($_FILES['picture']['tmp_name'], '../assets/images/' . $filename)) {
      // Update query to set user profile picture
      $sql = "UPDATE users SET profile_pic = '$filename' WHERE id_user = '$id_user'";
      if ($conn->query($sql)) {
        $_SESSION['message'] = "Profile picture updated successfully!";
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = "Error updating profile picture: " . $conn->error;
        $_SESSION['messagetype'] = 'error';
      }
    } else {
      $_SESSION['message'] = "Failed to upload file.";
      $_SESSION['messagetype'] = 'error';
    }
  } else {
    $_SESSION['message'] = "No file selected.";
    $_SESSION['messagetype'] = 'error';
  }
}

if (isset($_POST['companyPic'])) {
  $id_company = $_SESSION['id_company'];
  $profile_pic = $_FILES['picture']['name'];

  if ($profile_pic != '') {
    // Generate unique filename using company ID and timestamp
    $ext = pathinfo($profile_pic, PATHINFO_EXTENSION);
    $filename = 'company_' . $id_company . '_' . time() . '.' . $ext;

    if (move_uploaded_file($_FILES['picture']['tmp_name'], '../assets/images/' . $filename)) {
      // Update query to set company profile picture
      $sql = "UPDATE company SET profile_pic = '$filename' WHERE id_company = '$id_company'";
      if ($conn->query($sql)) {
        $_SESSION['message'] = "Profile picture updated successfully!";
        $_SESSION['messagetype'] = 'success';
      } else {
        $_SESSION['message'] = "Error updating profile picture: " . $conn->error;
        $_SESSION['messagetype'] = 'error';
      }
    } else {
      $_SESSION['message'] = "Failed to upload file.";
      $_SESSION['messagetype'] = 'error';
    }
  } else {
    $_SESSION['message'] = "No file selected.";
    $_SESSION['messagetype'] = 'error';
  }
}



header("location: ../dashboard/editProfile.php");
exit();
