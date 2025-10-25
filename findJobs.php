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
        // --- MAIN SQL QUERY USING JOINS & ALIASES ---
        $sql = "
            SELECT jp.*, c.profile_pic, d.name AS city_name, i.name AS industry_name, jt.type AS job_type_name
            FROM job_post jp
            LEFT JOIN company c ON jp.id_company = c.id_company
            LEFT JOIN districts_or_cities d ON jp.city_id = d.id
            LEFT JOIN industry i ON jp.industry_id = i.id
            LEFT JOIN job_type jt ON jp.job_status = jt.id
            WHERE 1=1
        ";

        // Keyword search in job title, description, responsibility, skills
        if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
            $keyword = mysqli_real_escape_string($conn, trim($_GET['keyword']));
            $sql .= " AND (jp.jobtitle LIKE '%$keyword%' 
                        OR jp.description LIKE '%$keyword%' 
                        OR jp.responsibility LIKE '%$keyword%' 
                        OR jp.skills_ability LIKE '%$keyword%')";
        }

        // Location filter
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $location = mysqli_real_escape_string($conn, $_GET['location']);
            $sql .= " AND jp.city_id = '$location'";
        }

        // Category filter
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = mysqli_real_escape_string($conn, $_GET['category']);
            $sql .= " AND jp.industry_id = '$category'";
        }

        // Job type filter
        if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
            $job_type = mysqli_real_escape_string($conn, $_GET['job_type']);
            $sql .= " AND jp.job_status = '$job_type'";
        }

        $sql .= " ORDER BY jp.createdat DESC";
        $result = $conn->query($sql);

        // --- OPTIONAL AGGREGATE / SUBQUERY EXAMPLE ---
        $subquery = "
            SELECT id_company, COUNT(*) AS total_jobs
            FROM job_post
            GROUP BY id_company
        ";
        $company_job_counts = $conn->query($subquery);

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
                $jobtitle = $row['jobtitle'];
                $minimum_salary = $row['minimumsalary'];
                $maximum_salary = $row['maximumsalary'];
                $create_date = $row['createdat'];

                // Already fetched via JOIN
                $location_name = $row['city_name'];
                $job_category_name = $row['industry_name'];
                $job_type_name = $row['job_type_name'];
                $profile_pic_path = $row['profile_pic'];
        ?>
            <a href="./jobDetails.php?key=<?php echo $hash . '&id=' . $job_id ?>">
                <div class="job-item-container">
                    <div class="profile-container">
                        <img src="./assets/images/<?php echo $profile_pic_path ?>" alt="">
                    </div>
                    <div class="job-info-container">
                        <div class="job-info-left-side">
                            <div class="job-status">
                                <i class="fa-solid fa-briefcase"></i>
                                <span><?php echo $job_type_name ?></span>
                            </div>
                            <h3> <?php echo $jobtitle; ?> </h3>
                            <div class="others-info">
                                <div class="job-category-info">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <span><?php echo $job_category_name ?></span>
                                </div>
                                <div class="salary-info">
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                    <span><?php echo $minimum_salary . "-" . $maximum_salary ?></span>
                                </div>
                                <div class="location-info">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span><?php echo $location_name ?></span>
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
        <?php 
            }
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
  </div>
  <?php include "./includes/sql_terminal.php" ?>
</body>
