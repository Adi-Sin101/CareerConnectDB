<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/conn.php";

if (isset($_GET['page'])) {
  if (isset($_GET['id'])) {
    $id_jobpost = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM job_post WHERE id_jobpost = '$id_jobpost'";
    $conn->query($sql);

    header("Location: ../dashboard/allJobPost.php");
    exit();
  }
} else {
  if (isset($_GET['id'])) {
    $id_jobpost = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM job_post WHERE id_jobpost = '$id_jobpost'";
    $conn->query($sql);

    header("Location: ../dashboard/manageJobs.php");
    exit();
  }
}
