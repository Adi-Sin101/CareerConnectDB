<?php include "../includes/conn.php"; ?>
<?php include "../includes/indexHeader.php"; ?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>

  <div class="dashboard-container">
    <?php include "../dashboard/dashboardSidebar.php" ?>
    <div class="add-job-container">
      <div class="add-job-content-container">
        <?php if (isset($_GET['id'])) :
          $id_jobpost = $_GET['id'];
          $hash = md5($id_jobpost);

          // SINGLE LEFT JOIN QUERY TO FETCH ALL RELATED DATA
          $sql = "
            SELECT jp.*, 
                   i.name AS industry_name, 
                   jt.type AS job_type_name,
                   e.name AS edu_name,
                   s.name AS state_name,
                   c.name AS city_name
            FROM job_post jp
            LEFT JOIN industry i ON jp.industry_id = i.id
            LEFT JOIN job_type jt ON jp.job_status = jt.id
            LEFT JOIN education e ON jp.edu_qualification = e.id
            LEFT JOIN states s ON jp.state_id = s.id
            LEFT JOIN districts_or_cities c ON jp.city_id = c.id
            WHERE jp.id_jobpost = '$id_jobpost'
          ";
          $query = $conn->query($sql);
          $row = $query->fetch_assoc();
        ?>
          <div class="headline">
            <h3>Edit Job</h3>
          </div>
          <form class="job-form-container" method="post" action="../process/updateJob.php?key=<?php echo $hash . '&id=' . $id_jobpost ?>">
            <div class="input-group item-a">
              <label for="job-title">Job Title/Designation</label>
              <input type="text" name="jobtitle" value="<?php echo $row['jobtitle']; ?>">
            </div>

            <div class="input-group item-b">
              <label for="job-title">Job Category</label>
              <div class="select-container">
                <select id="select-category" name="industry">
                  <?php 
                  $jobCategoryQuery = $conn->query("SELECT * FROM industry");
                  while ($jobCategory = $jobCategoryQuery->fetch_assoc()) { 
                  ?>
                    <option value="<?php echo $jobCategory['id'] ?>" <?php if($jobCategory['id'] == $row['industry_id']) echo 'selected'; ?>>
                      <?php echo $jobCategory['name'] ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>

            <div class="input-group  item-c">
              <label for="job-type">Job Type</label>
              <div class="select-container">
                <select id="select-category" name="job_type">
                  <?php 
                  $jobTypeQuery = $conn->query("SELECT * FROM job_type");
                  while ($jobType = $jobTypeQuery->fetch_assoc()) { 
                  ?>
                    <option value="<?php echo $jobType['id'] ?>" <?php if($jobType['id'] == $row['job_status']) echo 'selected'; ?>>
                      <?php echo $jobType['type'] ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>

            <div class="input-group  item-d">
              <label for="job-title">Experience (Years)</label>
              <input type="number" placeholder="Experience" min="0" required name="experience" value="<?php echo $row['experience'] ?>">
            </div>

            <div class="input-group  item-e">
              <label for="job-title">Edu. Qualification</label>
              <div class="select-container">
                <select id="select-category" name="edu_qualification">
                  <?php 
                  $educationQuery = $conn->query("SELECT * FROM education");
                  while ($education = $educationQuery->fetch_assoc()) { 
                  ?>
                    <option value="<?php echo $education['id'] ?>" <?php if($education['id'] == $row['edu_qualification']) echo 'selected'; ?>>
                      <?php echo $education['name'] ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>

            <div class="input-group  item-f">
              <label for="job-title">Division/State</label>
              <div class="select-container">
                <select id="select-category" name="division_or_state" required>
                  <?php 
                  $divisionOrStateQuery = $conn->query("SELECT * FROM states");
                  while ($divisionOrState = $divisionOrStateQuery->fetch_assoc()) { 
                  ?>
                    <option value="<?php echo $divisionOrState['id'] ?>" <?php if($divisionOrState['id'] == $row['state_id']) echo 'selected'; ?>>
                      <?php echo $divisionOrState['name'] ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>

            <div class="input-group  item-g">
              <label for="job-title">District/City</label>
              <div class="select-container">
                <select id="select-category" name="district_or_city">
                  <?php 
                  $districtOrCityQuery = $conn->query("SELECT * FROM districts_or_cities");
                  while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) { 
                  ?>
                    <option value="<?php echo $districtOrCity['id'] ?>" <?php if($districtOrCity['id'] == $row['city_id']) echo 'selected'; ?>>
                      <?php echo $districtOrCity['name'] ?>
                    </option>
                  <?php } ?>
                </select>
                <span class="custom-arrow"></span>
              </div>
            </div>

            <div class="input-group  item-h">
              <label for="job-title">Minimum Salary</label>
              <input type="number" placeholder="Min Salary" min="0" name="minimumsalary" required value="<?php echo $row['minimumsalary'] ?>">
            </div>

            <div class="input-group  item-i">
              <label for="job-title">Maximum Salary</label>
              <input type="number" placeholder="Max Salary" min="0" required name="maximumsalary" value="<?php echo $row['maximumsalary'] ?>">
            </div>

            <div class="input-group  item-j">
              <label for="job-title">Deadline</label>
              <input type="date" name="deadline" required value="<?php echo $row['deadline'] ?>">
            </div>

            <div class="input-group  item-k">
              <label for="job-title">Job Skills</label>
              <textarea cols="20" rows="5" placeholder="Skills..." name="skills" required><?php echo $row['skills_ability']; ?></textarea>
            </div>

            <div class="input-group  item-l">
              <label for="job-title">Job Description</label>
              <textarea cols="20" rows="5" placeholder="Job Description..." required name="description"><?php echo $row['description']; ?></textarea>
            </div>

            <div class="input-group  item-m">
              <label for="job-title">Responsibilities</label>
              <textarea cols="20" rows="5" placeholder="Responsibilites..." required name="responsibility"><?php echo $row['responsibility']; ?></textarea>
            </div>

            <div class="button-container item-n">
              <button type="submit" name="updateJob" class="btn btn-secondary">Update Job</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php include '../includes/sql_terminal.php'; ?>
</body>
