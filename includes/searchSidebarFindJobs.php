<div class="sidebar-container">
  <?php if (isset($_GET['keyword']) || isset($_GET['location']) || isset($_GET['category']) || isset($_GET['job_type'])): ?>
    <div class="clear-filters-container">
      <a href="findJobs.php" class="clear-filters-btn">
        <i class="fa-solid fa-times"></i> Clear All Filters
      </a>
    </div>
  <?php endif; ?>

  <div class="keyword-search-container">
    <div class="search-form-container">
      <form method="GET" action="findJobs.php">
        <div class="input-group">
          <label for="search-keyword">Search Keywords</label>
          <div class="line line-light line-light-left"></div>
          <div class="search-input-wrapper">
            <input type="search" name="keyword" placeholder="Search Keywords..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="location-search-container">
    <div class="search-form-container">
      <form method="GET" action="findJobs.php">
        <input type="hidden" name="keyword" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : ''; ?>">
        <input type="hidden" name="job_type" value="<?php echo isset($_GET['job_type']) ? htmlspecialchars($_GET['job_type']) : ''; ?>">
        <div class="input-group">
          <label for="location-keyword">Location</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="location">
              <option value="">All Location</option>
              <?php
              // INNER JOIN + DISTINCT
              $districtOrCitySql = "
                SELECT DISTINCT d.id, d.name
                FROM districts_or_cities AS d
                INNER JOIN job_post AS jp ON jp.city_id = d.id
                ORDER BY d.name ASC
              ";
              $districtOrCityQuery = $conn->query($districtOrCitySql);
              while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $districtOrCity['id'] ?>" <?php echo (isset($_GET['location']) && $_GET['location'] == $districtOrCity['id']) ? 'selected' : ''; ?>><?php echo $districtOrCity['name'] ?></option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn">Filter</button>
      </form>
    </div>
  </div>

  <div class="category-search-container">
    <div class="search-form-container">
      <form method="GET" action="findJobs.php">
        <input type="hidden" name="keyword" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        <input type="hidden" name="location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
        <input type="hidden" name="job_type" value="<?php echo isset($_GET['job_type']) ? htmlspecialchars($_GET['job_type']) : ''; ?>">
        <div class="input-group">
          <label for="category-keyword">Category</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="category">
              <option value="">All Categories</option>
              <?php
              // LEFT JOIN + GROUP BY + COUNT
              $jobCategorySql = "
                SELECT i.id, i.name, COUNT(jp.id_jobpost) AS total_jobs
                FROM industry AS i
                LEFT JOIN job_post AS jp ON jp.industry_id = i.id
                GROUP BY i.id, i.name
                ORDER BY total_jobs DESC
              ";
              $jobCategoryQuery = $conn->query($jobCategorySql);
              while ($jobCategory = $jobCategoryQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $jobCategory['id'] ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $jobCategory['id']) ? 'selected' : ''; ?>><?php echo $jobCategory['name'] ?> (<?php echo $jobCategory['total_jobs'] ?>)</option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn">Filter</button>
      </form>
    </div>
  </div>

  <div class="job-type-search-container">
    <div class="search-form-container">
      <form method="GET" action="findJobs.php">
        <input type="hidden" name="keyword" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        <input type="hidden" name="location" value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
        <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : ''; ?>">
        <div class="input-group">
          <label for="type-keyword">Job Type</label>
          <div class="line line-light line-light-left"></div>
          <div class="select-container">
            <select id="select-category" name="job_type">
              <option value="">All Job Types</option>
              <?php
              // LEFT JOIN + GROUP BY + COUNT + ALIAS
              $jobTypeSql = "
                SELECT jt.id, jt.type, COUNT(jp.id_jobpost) AS total_jobs
                FROM job_type AS jt
                LEFT JOIN job_post AS jp ON jp.job_status = jt.id
                GROUP BY jt.id, jt.type
                ORDER BY jt.type ASC
              ";
              $jobTypeQuery = $conn->query($jobTypeSql);
              while ($jobType = $jobTypeQuery->fetch_assoc()) {
              ?>
                <option value="<?php echo $jobType['id'] ?>" <?php echo (isset($_GET['job_type']) && $_GET['job_type'] == $jobType['id']) ? 'selected' : ''; ?>><?php echo $jobType['type'] ?> (<?php echo $jobType['total_jobs'] ?>)</option>
              <?php } ?>
            </select>
            <span class="custom-arrow"></span>
          </div>
        </div>
        <button type="submit" class="btn">Filter</button>
      </form>
    </div>
  </div>
</div>
