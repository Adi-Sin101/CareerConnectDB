<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>

<body>
  <?php include "./includes/indexNavbar.php" ?>

  <div id="find-jobs-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="full-width-banner glassmorphism">
            <div class="banner-headline-text-part">
              <h3>Discover Your Next Career Opportunity</h3>
              <div class="line line-dark"></div>
              <p class="banner-subtitle">Explore thousands of job opportunities tailored to your skills and location</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="page-content">
      <div class="page-content-left-side">
        <?php include "./includes/searchSidebarFindJobs.php" ?>
      </div>
      <div class="page-content-right-side">
        <div class="headline">
          <span class="icon-container">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <h3>Jobs Listing Results</h3>
        </div>
        <?php

        // Build the SQL query with filters
        $sql = "SELECT * FROM job_post WHERE 1=1";
        $params = array();

        // Keyword search in job title and description
        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
          $keyword = mysqli_real_escape_string($conn, trim($_GET['keyword']));
          $sql .= " AND (jobtitle LIKE '%$keyword%' OR description LIKE '%$keyword%' OR responsibility LIKE '%$keyword%' OR skills_ability LIKE '%$keyword%')";
        }

        // Location filter
        if (isset($_GET['location']) && !empty($_GET['location'])) {
          $location = mysqli_real_escape_string($conn, $_GET['location']);
          $sql .= " AND city_id = '$location'";
        }

        // Category filter
        if (isset($_GET['category']) && !empty($_GET['category'])) {
          $category = mysqli_real_escape_string($conn, $_GET['category']);
          $sql .= " AND industry_id = '$category'";
        }

        // Job type filter
        if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
          $job_type = mysqli_real_escape_string($conn, $_GET['job_type']);
          $sql .= " AND job_status = '$job_type'";
        }

        $sql .= " ORDER BY createdat DESC";
        $result = $conn->query($sql);

        // Display results count
        $total_results = $result ? $result->num_rows : 0;
        $has_filters = (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) ||
                      (isset($_GET['location']) && !empty($_GET['location'])) ||
                      (isset($_GET['category']) && !empty($_GET['category'])) ||
                      (isset($_GET['job_type']) && !empty($_GET['job_type']));

        echo "<div class='results-count'>";
        if ($has_filters) {
          echo "<p>Found <strong>$total_results</strong> job" . ($total_results != 1 ? "s" : "") . " matching your search</p>";
        } else {
          echo "<p>Showing all <strong>$total_results</strong> available job" . ($total_results != 1 ? "s" : "") . "</p>";
        }
        echo "</div>";

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
          $hash = md5($row['id_jobpost']);
          $job_id = $row['id_jobpost'];
          $id_company = $row['id_company'];
          $jobtitle = $row['jobtitle'];
          $city_id = $row['city_id'];
          $industry_id = $row['industry_id'];
          $job_status_id = $row['job_status'];
          $minimum_salary = $row['minimumsalary'];
          $maximum_salary = $row['maximumsalary'];
          $create_date = $row['createdat'];
          $location = $conn->query("SELECT name from districts_or_cities where id = '$city_id'");
          $job_category = $conn->query("SELECT name from industry where id = '$industry_id'");
          $job_type = $conn->query("SELECT type from job_type where id = '$job_status_id'");
          $profile_pic = $conn->query("SELECT profile_pic from company where id_company = '$id_company'");

          $location = $location->fetch_assoc();
          $job_category = $job_category->fetch_assoc();
          $job_type = $job_type->fetch_assoc();
          $profile_pic = $profile_pic->fetch_assoc();

        ?>
          <a href="./jobDetails.php?key=<?php echo $hash . '&id=' . $job_id ?>">
            <div class="job-item-container">
              <div class="profile-container">
                <img src="./assets/images/<?php echo $profile_pic['profile_pic'] ?>" alt="">
              </div>
              <div class="job-info-container">
                <div class="job-info-left-side">
                  <div class="job-status">
                    <i class="fa-solid fa-briefcase"></i>
                    <span><?php echo $job_type['type'] ?></span>
                  </div>
                  <h3> <?php echo $jobtitle; ?> </h3>
                  <div class="others-info">
                    <div class="job-category-info">
                      <i class="fa-solid fa-briefcase"></i>
                      <span><?php echo $job_category['name'] ?></span>
                    </div>
                    <div class="salary-info">
                      <i class="fa-solid fa-money-check-dollar"></i>
                      <span><?php echo $minimum_salary . "-" . $maximum_salary ?></span>
                    </div>
                    <div class="location-info">
                      <i class="fa-solid fa-location-dot"></i>
                      <span><?php echo $location['name'] ?></span>
                    </div>
                  </div>
                  <div class="date-info">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span><?php echo $create_date; ?></span>
                  </div>
                </div>
                <div class="job-info-right-side">
                  <?php
                  $deadline = date_create($row['deadline']);
                  $now = date_create(date("Y-m-d"));
                  if ($now < $deadline) {
                    echo '<span class="validity-active">Active</span>';
                  } else {
                    echo '<span class="validity-expired">Expired</span>';
                  }
                  ?>
                </div>
              </div>
            </div>
          </a>
        <?php }
        } else {
          // No jobs found
          echo '<div class="no-jobs-found">
                  <i class="fa-solid fa-search"></i>
                  <h3>No Jobs Found</h3>
                  <p>Try adjusting your search criteria or browse all available jobs.</p>
                  <a href="findJobs.php" class="btn btn-primary">View All Jobs</a>
                </div>';
        } ?>
      </div>
    </section>

    <!-- Footer -->
    <div id="footer">
      <!-- Footer Widgets -->
      <?php include 'includes/indexFooterWidgets.php';
      ?>
      <!-- Footer Copyrights -->
      <?php include 'includes/footer.php' ?>
    </div>
  </div>
</body>