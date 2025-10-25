<div class="sidebar-container">

  <div class="search-form-container">
    <h4>Search Companies</h4>
    <form method="post" action="searchCompany.php">
      <div class="input-group">
        <label for="location-search">Location</label>
        <div class="select-container">
          <select id="location-search" name="location-search">
            <option value="">All Locations</option>
            <?php
            // --- Subquery with WHERE IN ---
            // Fetch all districts_or_cities that belong to states which have at least one company registered
            $districtOrCitySql = "
                SELECT * FROM districts_or_cities 
                WHERE id IN (
                    SELECT DISTINCT city_id FROM company WHERE city_id IS NOT NULL
                )
                ORDER BY name ASC
            ";
            $districtOrCityQuery = $conn->query($districtOrCitySql);
            while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) {
            ?>
              <option value="<?php echo $districtOrCity['id'] ?>"><?php echo $districtOrCity['name'] ?></option>
            <?php } ?>
          </select>
          <span class="custom-arrow"></span>
        </div>
      </div>

      <div class="input-group">
        <label for="category-search">Industry</label>
        <div class="select-container">
          <select id="category-search" name="category-search">
            <option value="">All Industries</option>
            <?php
            // ---JOIN with GROUP BY and HAVING ---
            // Show industries that have more than one company listed
            $jobCategorySql = "
                SELECT i.id, i.name, COUNT(c.id_company) AS total_companies
                FROM industry i
                LEFT JOIN company c ON i.id = c.industry_id
                GROUP BY i.id, i.name
                HAVING COUNT(c.id_company) > 0
                ORDER BY i.name ASC
            ";
            $jobCategoryQuery = $conn->query($jobCategorySql);
            while ($jobCategory = $jobCategoryQuery->fetch_assoc()) {
            ?>
              <option value="<?php echo $jobCategory['id'] ?>">
                <?php echo $jobCategory['name'] . ' (' . $jobCategory['total_companies'] . ')'; ?>
              </option>
            <?php } ?>
          </select>
          <span class="custom-arrow"></span>
        </div>
      </div>

      <button type="submit" class="btn" name="submitSearch">Search</button>
    </form>
  </div>
</div>
