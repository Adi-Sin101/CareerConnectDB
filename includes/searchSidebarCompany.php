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
            $districtOrCitySql = "SELECT * from districts_or_cities";
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
            $jobCategorySql = "SELECT * from industry";
            $jobCategoryQuery = $conn->query($jobCategorySql);
            while ($jobCategory = $jobCategoryQuery->fetch_assoc()) {
            ?>
              <option value="<?php echo $jobCategory['id'] ?>"><?php echo $jobCategory['name'] ?></option>
            <?php } ?>
          </select>
          <span class="custom-arrow"></span>
        </div>
      </div>
      <button type="submit" class="btn" name="submitSearch">Search</button>
    </form>
  </div>
</div>