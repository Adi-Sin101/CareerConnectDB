<?php include "./includes/conn.php" ?>
<?php include "./includes/indexHeader.php"; ?>

<body>
  <?php include "./includes/indexNavbar.php" ?>
  <?php if (isset($_GET['key']) && isset($_GET['id'])) :
    $id_company = $_GET['id'];

    // --- Company Info with JOINs ---
    $sql = "
    SELECT c.*, s.name AS state_name, d.name AS city_name, i.name AS industry_name
    FROM company c
    JOIN states s ON c.state_id = s.id
    JOIN districts_or_cities d ON c.city_id = d.id
    JOIN industry i ON c.industry_id = i.id
    WHERE c.id_company = '$id_company'
    ";
    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    // --- Reviews Count & Average ---
    $reviewStatsSql = "
    SELECT COUNT(*) AS total_reviews
    FROM company_reviews
    WHERE company_id = '$id_company'
    ";
    $reviewStats = $conn->query($reviewStatsSql)->fetch_assoc();
    $reviewStats['avg_rating'] = 'N/A'; // Rating not stored in database

    // --- Reviews Details with JOIN ---
    $sql5 = "
    SELECT r.review, r.createdby, u.fullname, u.profile_pic
    FROM company_reviews r
    JOIN users u ON r.createdby = u.id_user
    WHERE r.company_id = '$id_company'
    ORDER BY r.id DESC
    ";
    $query5  = $conn->query($sql5);

  ?>
    <div id="browse-company-details">
      <div class="intro-banner">
        <div class="intro-banner-overlay">
          <div class="intro-banner-content">
            <div class="container glassmorphism">
              <div class="banner-headline-text-part">
                <div class="profile-container">
                  <img src="../assets/images/<?php echo $row['profile_pic'] ?>" alt="">
                </div>
                <div class="job-info-container">
                  <h3> <?php echo $row['companyname'] ?> </h3>
                  <div class="job-category-info">
                    <i class="fa-solid fa-briefcase"></i>
                    <span><?php echo $row['industry_name'] . " industry" ?></span>
                  </div>
                  <div class="location-info">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><?php echo $row['state_name'] . ", " . $row['city_name'] ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="company-details-page-content">
        <div class="company-details-page-content-left-side">
          <div class="company-details-page-content-left-side-about">
            <div class="headline">
              <span class="icon-container">
                <i class="fa-solid fa-circle-exclamation"></i>
              </span>
              <h3>About Company Details</h3>
            </div>
            <div class="image-container">
              <img src="./assets/images/aboutCompany.jpg" alt="">
            </div>
            <div class="company-about">
              <span><?php echo $row['aboutme'] ?></span>
            </div>
          </div>
          <div class="company-details-page-content-left-side-jobpost">
            <div class="headline">
              <span class="icon-container">
                <i class="fa-solid fa-briefcase"></i>
              </span>
              <h3>Company Jobs Posts</h3>
            </div>
            <?php
            // --- Job Posts with JOINs & ORDER BY ---
            $sql = "
            SELECT j.*, dc.name AS city_name, i.name AS industry_name, jt.type AS job_type, c.profile_pic
            FROM job_post j
            JOIN districts_or_cities dc ON j.city_id = dc.id
            JOIN industry i ON j.industry_id = i.id
            JOIN job_type jt ON j.job_status = jt.id
            JOIN company c ON j.id_company = c.id_company
            WHERE j.id_company = '$id_company'
            ORDER BY j.createdat DESC
            ";
            $result = $conn->query($sql);

            while ($row4 = $result->fetch_assoc()) {
              $job_id = $row4['id_jobpost'];
              $jobtitle = $row4['jobtitle'];
              $minimum_salary = $row4['minimumsalary'];
              $maximum_salary = $row4['maximumsalary'];
              $create_date = $row4['createdat'];
              $hash = md5($job_id);
            ?>
              <a href="./jobDetails.php?key=<?php echo $hash . '&id=' . $job_id ?>" class="job-item-container">
                <div class="profile-container">
                  <img src="../assets/images/<?php echo $row4['profile_pic'] ?>" alt="">
                </div>
                <div class="job-info-container">
                  <div class="job-info-left-side">
                    <div class="job-status">
                      <i class="fa-solid fa-briefcase"></i>
                      <span><?php echo $row4['job_type'] ?></span>
                    </div>
                    <h3> <?php echo $jobtitle; ?> </h3>
                    <div class="others-info">
                      <div class="job-category-info">
                        <i class="fa-solid fa-briefcase"></i>
                        <span><?php echo $row4['industry_name'] ?></span>
                      </div>
                      <div class="salary-info">
                        <i class="fa-solid fa-money-check-dollar"></i>
                        <span><?php echo $minimum_salary . "-" . $maximum_salary ?></span>
                      </div>
                      <div class="location-info">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $row4['city_name'] ?></span>
                      </div>
                    </div>
                    <div class="date-info">
                      <i class="fa-solid fa-calendar-days"></i>
                      <span><?php echo $create_date; ?></span>
                    </div>
                  </div>
                  <div class="job-info-right-side">
                    <?php
                    $deadline = date_create($row4['deadline']);
                    $now = date_create(date("y-m-d"));

                    if ($now < $deadline) {
                      echo "<span class='validity-active'>Active</span>";
                    } else {
                      echo "<span class='validity-expired'>Expired</span>";
                    }
                    ?>
                  </div>
                </div>
              </a>
            <?php }
            ?>
          </div>
          <div class="review-container-area">
            <div class="headline">
              <span class="icon-container">
                <i class="fa-solid fa-star"></i>
              </span>
              <h3>Review Company - Reviewed (<?php echo $reviewStats['total_reviews']; ?>) | Average Rating: <?php echo $reviewStats['avg_rating'] ?: 'N/A'; ?></h3>
            </div>
            <div class="review-section">
              <?php
              while ($row5 = $query5->fetch_assoc()) {
              ?>
                <div class="review-item">
                  <div class="review-item-profile-container">
                    <?php
                    if (!empty($row5['profile_pic'])) {
                      echo '<img src="./assets/images/' . $row5['profile_pic'] . '" alt="Profile Picture">';
                    } else {
                      echo "<img src='./assets/images/user.png' alt='Default Profile Picture'>";
                    }
                    ?>
                    <p><?php echo $row5['fullname']; ?></p>
                  </div>
                  <div class="review-item-review-container">
                    <p>"<?php echo $row5['review']; ?>"</p>
                  </div>
                </div>
              <?php } ?>
            </div>
            <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) : ?>
              <div class="review-container">
                <div class="star-widget">
                  <form action="./process/submitReview.php?key=<?php echo md5($id_company) . "&cid=" . $id_company ?>" method="post">
                    <input type="radio" name="rate5" id="rate-5">
                    <label for="rate-5" class="fas fa-star"></label>
                    <input type="radio" name="rate4" id="rate-4">
                    <label for="rate-4" class="fas fa-star"></label>
                    <input type="radio" name="rate3" id="rate-3">
                    <label for="rate-3" class="fas fa-star"></label>
                    <input type="radio" name="rate2" id="rate-2">
                    <label for="rate-2" class="fas fa-star"></label>
                    <input type="radio" name="rate1" id="rate-1">
                    <label for="rate-1" class="fas fa-star"></label>

                    <div class="review-form">
                      <header></header>
                      <div class="textarea">
                        <textarea name="company-review" cols="30" placeholder="Describe your experience.."></textarea>
                      </div>
                      <div class="button-container">
                        <button class="btn" type="submit" name="submit">Submit Review</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="company-details-page-content-right-side">
          <div class="information-wrapper">
            <div class="information-container">
              <div class="headline">
                <h3>More Information</h3>
              </div>
              <ul class="information-list-container">
                <li class="information-list-item">
                  <div class="icon-container">
                    <i class="fa-solid fa-phone"></i>
                  </div>
                  <div class="info-container">
                    <span>Phone Number:</span>
                    <span><?php echo $row['contactno'] ?></span>
                  </div>
                </li>
                <li class="information-list-item">
                  <div class="icon-container">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <div class="info-container">
                    <span>Email:</span>
                    <span><?php echo $row['email'] ?></span>
                  </div>
                </li>
                <li class="information-list-item">
                  <div class="icon-container">
                    <i class="fa-solid fa-location-dot"></i>
                  </div>
                  <div class="info-container">
                    <span>Location:</span>
                    <span><?php echo $row['city_name'] ?></span>
                  </div>
                </li>
                <li class="information-list-item">
                  <div class="icon-container">
                    <i class="fa-solid fa-globe"></i>
                  </div>
                  <div class="info-container">
                    <span>Website:</span>
                    <span><?php echo $row['website'] ?></span>
                  </div>
                </li>
                <li class="information-list-item">
                  <div class="icon-container">
                    <i class="fa-solid fa-building"></i>
                  </div>
                  <div class="info-container">
                    <span>Established:</span>
                    <span><?php echo $row['esta_date'] ?></span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php else :
    echo "No id is found";
  ?>
  <?php endif; ?>
  <?php include './includes/sql_terminal.php'; ?>
</body>
