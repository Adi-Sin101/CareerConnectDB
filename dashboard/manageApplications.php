<?php
session_start();
include "../includes/conn.php";
?>
<?php include "../includes/indexHeader.php" ?>

<body>
  <?php include "../includes/indexNavbar.php" ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php" ?>
    <div class="manage-jobs-container">
      <div class="headline">
        <h3>Job Applications</h3>
      </div>

      <?php
      $id_company = $_SESSION['id_company'] ?? null;

      if (!$id_company) {
        echo "<div class='no-applications'>Please log in as a company to view applications.</div>";
        exit();
      }

      // SINGLE JOIN QUERY TO GET JOBS WITH APPLICATION COUNT, CITY, INDUSTRY, JOB TYPE, PROFILE PIC
      $sql = "
        SELECT jp.id_jobpost, jp.jobtitle, jp.createdat, jp.minimumsalary, jp.maximumsalary,
               jt.type AS job_type, i.name AS industry_name, s.name AS city_name,
               c.profile_pic,
               COUNT(aj.id_user) AS applications_count
        FROM job_post jp
        LEFT JOIN job_type jt ON jp.job_status = jt.id
        LEFT JOIN industry i ON jp.industry_id = i.id
        LEFT JOIN districts_or_cities s ON jp.city_id = s.id
        LEFT JOIN company c ON jp.id_company = c.id_company
        LEFT JOIN applied_jobposts aj ON jp.id_jobpost = aj.id_jobpost
        WHERE jp.id_company = '$id_company'
        GROUP BY jp.id_jobpost
        ORDER BY jp.id_jobpost DESC
      ";

      $query = $conn->query($sql);

      if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
          $jobHash = md5($row['id_jobpost']);
      ?>
          <div class="job-item-container">
            <div class="profile-container">
              <img src="../assets/images/<?php echo $row['profile_pic'] ?>" alt="">
            </div>
            <div class="job-info-container">
              <div>
                <a class="validity-active btn-green" href="viewApplications.php?hash=<?php echo $jobHash ?>&id=<?php echo $row['id_jobpost'] ?>">
                  <?php echo $row['applications_count'] ?> Applications 
                  <i class="fa-regular fa-eye" style="color:white; font-size:.85rem;"></i>
                </a>
              </div>
              <div class="title-with-status">
                <h3><?php echo $row["jobtitle"] ?></h3>
                <div class="job-status">
                  <i class="fa-solid fa-briefcase"></i>
                  <span><?php echo $row['job_type'] ?></span>
                </div>
                <span class="validity-active btn-green">Active</span>
              </div>
              <div class="others-info">
                <div class="job-category-info">
                  <i class="fa-solid fa-briefcase"></i>
                  <span><?php echo $row['industry_name'] ?></span>
                </div>
                <div class="date-info">
                  <i class="fa-solid fa-calendar-days"></i>
                  <span><?php echo $row['createdat'] ?></span>
                </div>
                <div class="salary-info">
                  <i class="fa-solid fa-money-check-dollar"></i>
                  <span><?php echo $row["minimumsalary"] . "-" . $row["maximumsalary"] ?></span>
                </div>
                <div class="location-info">
                  <i class="fa-solid fa-location-dot"></i>
                  <span><?php echo $row['city_name'] ?></span>
                </div>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<p>No job postings found.</p>";
      }
      ?>
    </div>
  </div>
  <?php include '../includes/sql_terminal.php'; ?>
</body>
