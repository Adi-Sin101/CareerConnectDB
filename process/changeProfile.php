<?php
include "../includes/session.php";

// Helper function to sanitize input
function clean($conn, $str) {
    return mysqli_real_escape_string($conn, trim($str));
}

if (isset($_SESSION['role_id'])) {
    $role = $_SESSION['role_id'];
    if ($role == 1 && isset($_POST['myProfile'])) {
        // Jobseeker profile update
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

        // Update query for user profile
        $sql = "UPDATE users SET fullname='$fullname', email='$email', aboutme='$aboutme', headline='$headline', contactno='$phoneno', dob='$dob', gender='$gender', state_id='$state_id', city_id='$city_id', address='$address', education_id='$education_id', skills='$skills' WHERE id_user='$id_user'";
        if ($conn->query($sql)) {
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['messagetype'] = 'success';
        } else {
            $_SESSION['message'] = "Error updating profile: " . $conn->error;
            $_SESSION['messagetype'] = 'error';
        }
        header("Location: ../dashboard/editProfile.php");
        exit();
    }
    if ($role == 2 && isset($_POST['companyProfile'])) {
        // Company profile update
        $id_company = $_SESSION['id_company'];
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

        // Update query for company profile
        $sql = "UPDATE company SET companyname='$companyname', email='$email', aboutme='$aboutme', industry_id='$industry_id', contactno='$phoneno', esta_date='$esta_date', empno='$empno', state_id='$state_id', city_id='$city_id', address='$address', website='$website' WHERE id_company='$id_company'";
        if ($conn->query($sql)) {
            $_SESSION['message'] = "Profile updated successfully!";
            $_SESSION['messagetype'] = 'success';
        } else {
            $_SESSION['message'] = "Error updating profile: " . $conn->error;
            $_SESSION['messagetype'] = 'error';
        }
        header("Location: ../dashboard/editProfile.php");
        exit();
    }
    // Removed admin profile update as admin functionality was removed
}
// If nothing matched, redirect back with error
$_SESSION['message'] = "Invalid request or session expired.";
$_SESSION['messagetype'] = 'error';
header("Location: ../dashboard/editProfile.php");
exit();
