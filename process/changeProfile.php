<?php
include "../includes/conn.php";
session_start();

// Helper function to sanitize input
function clean($conn, $str) {
    return mysqli_real_escape_string($conn, trim($str));
}

if (isset($_SESSION['role_id'])) {
    $role = $_SESSION['role_id'];
    if ($role == 1 && isset($_POST['myProfile'])) {
        // Jobseeker update
        $id_user = $_SESSION['user_id'];
        $fullname = clean($conn, $_POST['fullname']);
        $email = clean($conn, $_POST['email']);
        $aboutme = clean($conn, $_POST['aboutme']);
        $headline = clean($conn, $_POST['headline']);
        $phoneno = clean($conn, $_POST['phoneno']);
        $dob = clean($conn, $_POST['dob']);
        $gender = clean($conn, $_POST['gender']);
        $state_id = clean($conn, $_POST['region']);
        $city_id = clean($conn, $_POST['city']);
        $address = clean($conn, $_POST['address']);
        $education_id = clean($conn, $_POST['education']);
        $skills = clean($conn, $_POST['skills']);
        $sql = "UPDATE users SET fullname='$fullname', email='$email', aboutme='$aboutme', headline='$headline', contactno='$phoneno', dob='$dob', gender='$gender', state_id='$state_id', city_id='$city_id', address='$address', education_id='$education_id', skills='$skills' WHERE id_user='$id_user'";
        $conn->query($sql);
        header("Location: ../dashboard/editProfile.php?success=1");
        exit();
    }
    if ($role == 2 && isset($_POST['companyProfile'])) {
        // Company update
        $id_company = $_SESSION['user_id'];
        $companyname = clean($conn, $_POST['companyname']);
        $email = clean($conn, $_POST['email']);
        $aboutme = clean($conn, $_POST['aboutme']);
        $industry_id = clean($conn, $_POST['industry']);
        $phoneno = clean($conn, $_POST['phoneno']);
        $esta_date = clean($conn, $_POST['esta_date']);
        $empno = clean($conn, $_POST['empno']);
        $state_id = clean($conn, $_POST['region']);
        $city_id = clean($conn, $_POST['city']);
        $address = clean($conn, $_POST['address']);
        $website = clean($conn, $_POST['website']);
        $sql = "UPDATE company SET companyname='$companyname', email='$email', aboutme='$aboutme', industry_id='$industry_id', contactno='$phoneno', esta_date='$esta_date', empno='$empno', state_id='$state_id', city_id='$city_id', address='$address', website='$website' WHERE id_company='$id_company'";
        $conn->query($sql);
        header("Location: ../dashboard/editProfile.php?success=1");
        exit();
    }
    if ($role == 3 && isset($_POST['aProfile'])) {
        // Admin update
        $id_admin = $_SESSION['id_admin'];
        $fullname = clean($conn, $_POST['fullname']);
        $email = clean($conn, $_POST['email']);
        $phoneno = clean($conn, $_POST['phoneno']);
        $dob = clean($conn, $_POST['dob']);
        $gender = clean($conn, $_POST['gender']);
        $address = clean($conn, $_POST['address']);
        $sql = "UPDATE admin SET fullname='$fullname', email='$email', contactno='$phoneno', dob='$dob', gender='$gender', address='$address' WHERE id_admin='$id_admin'";
        $conn->query($sql);
        header("Location: ../dashboard/editProfile.php?success=1");
        exit();
    }
}
// If nothing matched, redirect back
header("Location: ../dashboard/editProfile.php?error=1");
exit();
