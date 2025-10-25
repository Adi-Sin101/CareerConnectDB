<?php include "../includes/conn.php"; ?>
<?php include "../includes/indexHeader.php" ?>

<body>
  <?php include "../includes/indexNavbar.php" ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php" ?>
    <div class="view-applicants-container">
      <div class="headline">
        <h3>Applicants</h3>
      </div>

      <?php
      if (isset($_GET['id']) && isset($_SESSION['id_company'])) {
        $id_company = $_SESSION['id_company'];
        $job = $_GET['id'];

        // SINGLE JOIN QUERY TO FETCH APPLICANTS WITH EDUCATION AND CITY
        $sql = "
          SELECT aj.*, u.fullname, u.email, u.contactno, u.profile_pic, u.resume,
                 e.name AS education_name,
                 c.name AS city_name
          FROM applied_jobposts aj
          LEFT JOIN users u ON aj.id_user = u.id_user
          LEFT JOIN education e ON u.education_id = e.id
          LEFT JOIN districts_or_cities c ON u.city_id = c.id
          WHERE aj.id_company = '$id_company' AND aj.id_jobpost = '$job'
        ";

        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
          while ($row = $query->fetch_assoc()) {
      ?>
            <div class="job-item-container">
              <div class="profile-container">
                <img src="../assets/images/<?php echo $row['profile_pic'] ?>" alt="">
              </div>
              <div class="job-info-container">
                <div class="job-info-container-left-side">
                  <div class="applicants-info">
                    <i class="fa-solid fa-user"></i>
                    <span><?php echo $row['fullname'] ?></span>
                  </div>
                  <div class="email-info">
                    <i class="fa-solid fa-envelope"></i>
                    <span><?php echo $row['email'] ?></span>
                  </div>
                  <div class="education-info">
                    <i class="fa-solid fa-building-columns"></i>
                    <span><?php echo $row['education_name'] ?? 'Unknown' ?></span>
                  </div>
                  <div class="others-info">
                    <div class="contact-info">
                      <i class="fa-solid fa-phone"></i>
                      <span><?php echo $row['contactno'] ?? 'Unknown' ?></span>
                    </div>
                    <div class="location-info">
                      <i class="fa-solid fa-location-dot"></i>
                      <span><?php echo $row['city_name'] ?? 'Unknown' ?></span>
                    </div>
                  </div>
                </div>
                <div class="job-info-container-right-side">
                  <?php
                  $filePath = "../assets/resumes/" . $row['resume'];
                  if (!empty($row['resume']) && file_exists($filePath)) {
                    echo '<a href="' . $filePath . '" class="btn" download><i class="fa-solid fa-download" style="color:white; font-size:1rem;margin-right:.25rem;"></i>Resume</a>';
                  } else {
                    echo '<a href="#" class="btn btn-disabled"><i class="fa-solid fa-circle-xmark" style="color:white; font-size:1rem;margin-right:.25rem;"></i>No Resume</a>';
                  }
                  ?>
                </div>
              </div>
            </div>
      <?php
          }
        } else {
          echo "<p>No applicants found.</p>";
        }
      }
      ?>
    </div>
  </div>
  <?php include '../includes/sql_terminal.php'; ?>
</body>
