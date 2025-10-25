<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>

<body>
  <?php include "./includes/indexNavbar.php" ?>

  <div id="browse-company-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container">
            <div class="banner-headline-text-part glassmorphism">
              <h3>Search Companies</h3>
              <div class="line line-dark"></div>
              <span>Find companies based on your preferences. Filter by location or industry to discover the perfect opportunities.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <section class="page-content">
      <div class="page-content-left-side">
        <?php include "./includes/searchSidebarCompany.php"; ?>
      </div>

      <div class="page-content-right-side">
        <?php
        if (isset($_POST['submitSearch'])) {
          $conditions = [];

          if (!empty($_POST['location-search'])) {
            $district_or_city_id = $_POST['location-search'];
            $conditions[] = "c.city_id = '$district_or_city_id'";
          }

          if (!empty($_POST['category-search'])) {
            $industry_id = $_POST['category-search'];
            $conditions[] = "c.industry_id = '$industry_id'";
          }

          // --- LAB TOPIC: CASE WHEN + COALESCE ---
          // Use CASE WHEN for conditional text and COALESCE to handle NULLs
          if (empty($conditions)) {
            // --- LAB TOPIC: UNION ---
            // Combine two different company groups — active & recently added
            $sql = "
              SELECT id_company, companyname, profile_pic, industry_id, state_id, city_id, createdAt
              FROM company
              WHERE active = 1
              UNION
              SELECT id_company, companyname, profile_pic, industry_id, state_id, city_id, createdAt
              FROM company
              WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              ORDER BY createdAt DESC
            ";
          } else {
            $sql = "
              SELECT 
                c.id_company,
                c.companyname,
                c.profile_pic,
                COALESCE(i.name, 'Unknown Industry') AS industry_name,
                COALESCE(s.name, 'Unknown State') AS state_name,
                COALESCE(d.name, 'Unknown City') AS city_name,
                COUNT(j.id_jobpost) AS job_count,
                CASE 
                    WHEN COUNT(j.id_jobpost) = 0 THEN 'No Jobs Available'
                    WHEN COUNT(j.id_jobpost) = 1 THEN '1 Job Available'
                    ELSE CONCAT(COUNT(j.id_jobpost), ' Jobs Available')
                END AS job_status
              FROM company c
              LEFT JOIN industry i ON c.industry_id = i.id
              LEFT JOIN states s ON c.state_id = s.id
              LEFT JOIN districts_or_cities d ON c.city_id = d.id
              LEFT JOIN job_post j ON c.id_company = j.id_company
              WHERE " . implode(' AND ', $conditions) . "
              GROUP BY c.id_company, c.companyname, i.name, s.name, d.name, c.profile_pic
              ORDER BY c.createdAt DESC
            ";
          }

          $query = $conn->query($sql);

          if ($query->num_rows < 1) {
            echo '<div class="no-companies">
                <p>No companies found matching your search...</p> 
              </div>';
          } else {
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_company']);
              $id_company = $row['id_company'];
        ?>
              <a href="./companyDetails.php?key=<?php echo $hash . '&id=' . $id_company ?>" class="company-card-link">
                <div class="company-card">
                  <div class="company-profile">
                    <img src="assets/images/<?php echo $row['profile_pic'] ?>" alt="<?php echo $row['companyname'] ?> Logo">
                  </div>
                  <div class="company-info">
                    <h3><?php echo $row['companyname'] ?></h3>
                    <div class="company-details">
                      <div class="detail-item">
                        <i class="fa-solid fa-briefcase"></i>
                        <span><?php echo $row['industry_name'] ?> Industry</span>
                      </div>
                      <div class="detail-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $row['state_name'] . ', ' . $row['city_name']; ?></span>
                      </div>
                      <div class="detail-item">
                        <i class="fa-solid fa-building"></i>
                        <span><?php echo $row['job_status']; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
        <?php
            }
          }
        } else {
          // --- LAB TOPIC: UNION (show both active and new companies) ---
          $sql = "
            SELECT id_company, companyname, profile_pic, industry_id, state_id, city_id, createdAt
            FROM company
            WHERE active = 1
            UNION
            SELECT id_company, companyname, profile_pic, industry_id, state_id, city_id, createdAt
            FROM company
            WHERE createdAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ORDER BY createdAt DESC
          ";
          $query = $conn->query($sql);

          if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_company']);
              $id_company = $row['id_company'];
              $industry_id = $row['industry_id'];
              $state_id = $row['state_id'];
              $city_id = $row['city_id'];

              $industry = $conn->query("SELECT name FROM industry WHERE id = '$industry_id'")->fetch_assoc();
              $industry_name = $industry ? $industry['name'] : 'Unknown Industry';

              $division_or_state = $conn->query("SELECT name FROM states WHERE id = '$state_id'")->fetch_assoc();
              $state_name = $division_or_state ? $division_or_state['name'] : 'Unknown State';

              $district_or_city = $conn->query("SELECT name FROM districts_or_cities WHERE id = '$city_id'")->fetch_assoc();
              $city_name = $district_or_city ? $district_or_city['name'] : 'Unknown City';

              $job_count_query = $conn->query("SELECT COUNT(*) as job_count FROM job_post WHERE id_company = '$id_company'");
              $job_count_row = $job_count_query->fetch_assoc();
              $job_count = $job_count_row['job_count'];
        ?>
              <a href="./companyDetails.php?key=<?php echo $hash . '&id=' . $id_company ?>" class="company-card-link">
                <div class="company-card">
                  <div class="company-profile">
                    <img src="assets/images/<?php echo $row['profile_pic'] ?>" alt="<?php echo $row['companyname'] ?> Logo">
                  </div>
                  <div class="company-info">
                    <h3><?php echo $row['companyname'] ?></h3>
                    <div class="company-details">
                      <div class="detail-item">
                        <i class="fa-solid fa-briefcase"></i>
                        <span><?php echo $industry_name ?> Industry</span>
                      </div>
                      <div class="detail-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $state_name . ', ' . $city_name; ?></span>
                      </div>
                      <div class="detail-item">
                        <i class="fa-solid fa-building"></i>
                        <span><?php echo $job_count ?> Jobs Available</span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
        <?php
            }
          } else {
            echo '<div class="no-companies"><p>No companies available.</p></div>';
          }
        }
        ?>
      </div>
    </section>

    <div id="footer">
      <?php include 'includes/indexFooterWidgets.php'; ?>
      <?php include 'includes/footer.php'; ?>
    </div>
  </div>
  <?php include './includes/sql_terminal.php'; ?>
</body>
