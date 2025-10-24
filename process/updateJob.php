<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/conn.php";

if (isset($_GET['id'])) {

  if (isset($_POST['updateJob'])) {
    $id_jobpost = mysqli_real_escape_string($conn, $_GET['id']);
    $id_company = isset($_SESSION['id_company']) ? mysqli_real_escape_string($conn, $_SESSION['id_company']) : (isset($_SESSION['user_id']) ? mysqli_real_escape_string($conn, $_SESSION['user_id']) : null);

    if (!$id_company) {
      header('Location: ../signin.php');
      exit();
    }

    $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
    $industry = mysqli_real_escape_string($conn, $_POST['industry']);
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $edu_qualification = mysqli_real_escape_string($conn, $_POST['edu_qualification']);
    $division_or_state_id = mysqli_real_escape_string($conn, $_POST['division_or_state']);
    $district_or_city_id = mysqli_real_escape_string($conn, $_POST['district_or_city']);
    $minimumsalary = mysqli_real_escape_string($conn, $_POST['minimumsalary']);
    $maximumsalary = mysqli_real_escape_string($conn, $_POST['maximumsalary']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    $skills_ability = mysqli_real_escape_string($conn, $_POST['skills']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $responsibility = mysqli_real_escape_string($conn, $_POST['responsibility']);



    $sql = "UPDATE job_post
        SET
            id_company = '$id_company',
            jobtitle = '$jobtitle',
            industry_id = '$industry',
            job_status = '$job_type',
            experience = '$experience',
            edu_qualification = '$edu_qualification',
            state_id = '$division_or_state_id',
            city_id = '$district_or_city_id',
            minimumsalary = '$minimumsalary',
            maximumsalary = '$maximumsalary',
            deadline = '$deadline',
            skills_ability = '$skills_ability',
            description = '$description',
            responsibility = '$responsibility'
        WHERE
            id_jobpost = '$id_jobpost'";

    if ($conn->query($sql)) {
      // Success - redirect to manage jobs
      header('Location: ../dashboard/manageJobs.php?success=1');
      exit();
    } else {
      // Error - redirect with error message
      header('Location: ../dashboard/manageJobs.php?error=1');
      exit();
    }
  }
}

header('Location: ../dashboard/manageJobs.php');
exit();
