<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>

<body>
  <?php include "./includes/indexNavbar.php" ?>
  <div id="browse-company-page">
    <div class="intro-banner">
      <div class="intro-banner-overlay">
        <div class="intro-banner-content">
          <div class="container glassmorphism">
            <div class="banner-headline-text-part">
              <h3>Browse Companies</h3>
              <div class="line line-dark"></div>
              <span>Discover a diverse range of companies and explore their offerings. Whether you're seeking employment opportunities or looking to learn more about different industries, our curated list of companies has got you covered.</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="page-content">
      <div class="page-content-left-side">
        <?php include "./includes/searchSidebarCompany.php" ?>
      </div>

      <div class="page-content-right-side">
        <?php
        // INNER JOIN with multiple tables to fetch company info ---
        $sql = "
          SELECT 
            c.id_company, 
            c.companyname, 
            c.profile_pic,
            i.name AS industry_name, 
            s.name AS state_name, 
            d.name AS city_name,
            COUNT(j.id_jobpost) AS job_count
          FROM company c
          LEFT JOIN industry i ON c.industry_id = i.id
          LEFT JOIN states s ON c.state_id = s.id
          LEFT JOIN districts_or_cities d ON c.city_id = d.id
          LEFT JOIN job_post j ON c.id_company = j.id_company
          GROUP BY c.id_company, c.companyname, c.profile_pic, i.name, s.name, d.name
          ORDER BY c.companyname ASC
        ";

        $query = $conn->query($sql);

        if ($query && $query->num_rows > 0) {
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
                    <span><?php echo $row['industry_name'] ?? 'Unknown Industry'; ?> Industry</span>
                  </div>
                  <div class="detail-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?php echo ($row['state_name'] ?? 'Unknown State') . ', ' . ($row['city_name'] ?? 'Unknown City'); ?></span>
                  </div>
                  <div class="detail-item">
                    <i class="fa-solid fa-building"></i>
                    <span><?php echo $row['job_count'] ?> Jobs Available</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        <?php }
        } else {

          // Create a simple view 
          $conn->query("
            CREATE OR REPLACE VIEW active_companies_view AS
            SELECT id_company, companyname FROM company WHERE active = 1
          ");
          echo '<div class="no-companies"><p>No companies found. (Active company view created for demonstration.)</p></div>';
        } ?>
      </div>
    </section>
  </div>

  <?php include './includes/sql_terminal.php'; ?>
</body>
