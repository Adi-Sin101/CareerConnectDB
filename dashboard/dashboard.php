<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/conn.php";
include "../includes/indexHeader.php";
?>
<body>
  <?php include "../includes/indexNavbar.php"; ?>
  <div class="dashboard-main-theme">
    <div class="dashboard-container">
      <?php include "./dashboardSidebar.php"; ?>
      <div class="dashboard-content-container themed-content">
        <?php if ((isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1)) : ?>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-briefcase"></i>
            </div>
            <h1>
              <?php
              $id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
              $sql  = "select * from applied_jobposts where id_user = '$id_user'";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Applied Jobs</span>
          </div>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-sharp fa-solid fa-heart"></i>
            </div>
            <h1>
              <?php
              $id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
              $sql  = "select * from saved_jobposts where id_user = '$id_user'";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Saved Jobs</span>
          </div>
        <?php endif; ?>
        <?php if ((isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2 && isset($_SESSION['id_company']))) : ?>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-briefcase-medical"></i>
            </div>
            <h1>
              <?php
              $id_company = $_SESSION['id_company'];
              $sql = "SELECT * FROM job_post WHERE id_company = '$id_company'";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Jobs Posted</span>
          </div>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-star"></i>
            </div>
            <h1>
              <?php
              $id_company = $_SESSION['id_company'];
              $sql = "SELECT * FROM company_reviews JOIN company on company_reviews.company_id=company.id_company WHERE id_company = '$id_company'";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Reviews</span>
          </div>
        <?php endif; ?>
        <?php if ((isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3)) : ?>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-briefcase-medical"></i>
            </div>
            <h1>
              <?php
              $sql = "SELECT * FROM job_post";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Jobs</span>
          </div>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-building"></i>
            </div>
            <h1>
              <?php
              $sql = "SELECT * FROM company";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Companies/Employers</span>
          </div>
          <div class="container-box themed-box">
            <div class="icon themed-icon">
              <i class="fa-solid fa-user"></i>
            </div>
            <h1>
              <?php
              $sql = "SELECT * FROM users";
              $query = $conn->query($sql);
              echo $query->num_rows;
              ?>
            </h1>
            <span>Job Seekers</span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>