<?php
include "../includes/session.php";
include "../includes/conn.php"; // Make sure $conn is available

if (isset($_GET['cid']) && isset($_POST['submit'])) {
    $id_user = $_SESSION['id_user'];
    $id_company = $_GET['cid'];
    $review = trim($_POST['company-review']);
    $createdat = date("Y-m-d");
    $hash  = md5($id_company);

    // Determine rating from the radio buttons
    $rate = 0; // default if no rate selected
    if (isset($_POST['rate1'])) $rate = 1;
    if (isset($_POST['rate2'])) $rate = 2;
    if (isset($_POST['rate3'])) $rate = 3;
    if (isset($_POST['rate4'])) $rate = 4;
    if (isset($_POST['rate5'])) $rate = 5;

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO company_reviews (company_id, createdby, review, rating, createdat) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $id_company, $id_user, $review, $rate, $createdat);

    if ($stmt->execute()) {
        header("Location: ../companyDetails.php?key=" . $hash . "&id=" . $id_company);
        exit();
    } else {
        echo "Error submitting review: " . $conn->error;
    }

    $stmt->close();
}
?>
