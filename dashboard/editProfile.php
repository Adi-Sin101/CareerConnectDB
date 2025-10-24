

<?php include "../includes/conn.php"; ?>
<?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } ?>
<?php include "../includes/indexHeader.php"; ?>
<body>
  <?php include "../includes/indexNavbar.php"; ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>
    <div class="dashboard-inner-container">
      <!-- Profile Section: Jobseeker -->
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
        <?php
          $id_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
          if ($id_user) {
            $sql = "select * from users where id_user='$id_user'";
            $query = $conn->query($sql);
            $row = $query->fetch_assoc();
            $state_id = $row['state_id'];
            $sql1 = "SELECT * from states where id = '$state_id'";
            $query1 = $conn->query($sql1);
            $row1 = $query1->fetch_assoc();
            $city_id = $row['city_id'];
            $sql2 = "SELECT * from districts_or_cities where id = '$city_id'";
            $query2 = $conn->query($sql2);
            $row2 = $query2->fetch_assoc();
            $education_id = $row['education_id'];
            $sql4 = "SELECT * from education where id='$education_id'";
            $query4 = $conn->query($sql4);
            $row4 = $query4->fetch_assoc();
        ?>
        <section class="profile-card jobseeker-profile">
          <header>
            <div class="profile-pic-wrap">
              <img src="../assets/images/<?php echo htmlspecialchars($row['profile_pic'] ?: 'user.png'); ?>" alt="Profile Picture" class="profile-pic" />
              <form method="post" action="../process/changePic.php" enctype="multipart/form-data">
                <input type="file" name="picture" accept="image/*" class="file-upload">
                <button type="submit" name="myPic" class="btn btn-secondary">Change Picture</button>
              </form>
            </div>
            <h2><?php echo htmlspecialchars($row['fullname']); ?></h2>
            <span class="profile-role">Jobseeker</span>
          </header>
          <main>
            <form action="../process/changeProfile.php" method="post" class="profile-form">
              <div class="form-row">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>
              </div>
              <div class="form-row">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
              </div>
              <div class="form-row">
                <label>About</label>
                <textarea name="aboutme" required><?php echo htmlspecialchars($row['aboutme']); ?></textarea>
              </div>
              <div class="form-row">
                <label>Headline</label>
                <input type="text" name="headline" value="<?php echo htmlspecialchars($row['headline']); ?>">
              </div>
              <div class="form-row">
                <label>Phone Number</label>
                <input type="text" name="phoneno" value="<?php echo htmlspecialchars($row['contactno']); ?>">
              </div>
              <div class="form-row">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>">
              </div>
              <div class="form-row">
                <label>Gender</label>
                <input type="text" name="gender" value="<?php echo htmlspecialchars($row['gender']); ?>">
              </div>
              <div class="form-row">
                <label>Division/State</label>
                <select name="region">
                  <?php
                  if (!empty($state_id)) {
                    echo '<option value="' . $state_id . '" selected>' . htmlspecialchars($row1['name']) . '</option>';
                  } else {
                    echo '<option value="">Select Division/State</option>';
                  }
                  $divisionSql = "SELECT * from states";
                  $divisionQuery = $conn->query($divisionSql);
                  while ($division = $divisionQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $division['id'] ?>"><?php echo htmlspecialchars($division['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>City/District</label>
                <select name="city">
                  <?php
                  if (!empty($city_id)) {
                    echo '<option value="' . $city_id . '" selected>' . htmlspecialchars($row2['name']) . '</option>';
                  } else {
                    echo '<option value="">Select District/City</option>';
                  }
                  $districtOrCitySql = "SELECT * from districts_or_cities";
                  $districtOrCityQuery = $conn->query($districtOrCitySql);
                  while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $districtOrCity['id'] ?>"><?php echo htmlspecialchars($districtOrCity['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
              </div>
              <div class="form-row">
                <label>Highest Education</label>
                <select name="education">
                  <?php
                  if (!empty($education_id)) {
                    echo '<option value="' . $education_id . '" selected>' . htmlspecialchars($row4['name']) . '</option>';
                  } else {
                    echo '<option value="">Select Degree...</option>';
                  }
                  $educationSql = "SELECT * from education";
                  $educationQuery = $conn->query($educationSql);
                  while ($education = $educationQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $education['id'] ?>"><?php echo htmlspecialchars($education['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>Skills</label>
                <textarea name="skills" required><?php echo htmlspecialchars($row['skills']); ?></textarea>
              </div>
              <div class="form-row">
                <button type="submit" name="myProfile" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
            <div class="security-section">
              <form method="post" action="../process/changePassword.php">
                <label>New Password</label>
                <input name="newPassword" type="password" minlength="6" placeholder="New Password">
                <label>Confirm Password</label>
                <input type="password" minlength="6" placeholder="Confirm New Password">
                <button type="submit" name="myPassword" class="btn btn-secondary">Update Password</button>
              </form>
              <form action="../process/deactivate.php" method="post">
                <button type="submit" name="userProfile" class="btn btn-danger">Deactivate Account</button>
              </form>
            </div>
          </main>
        </section>
        <?php } ?>
      <?php endif; ?>

      <!-- Profile Section: Employer (Company) -->
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2): ?>
        <?php
          $id_company = isset($_SESSION['id_company']) ? $_SESSION['id_company'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
          if ($id_company) {
            $sql = "select * from company where id_company='$id_company'";
            $query = $conn->query($sql);
            $row = $query->fetch_assoc();
            $state_id = $row['state_id'];
            $sql1 = "SELECT * from states where id = '$state_id'";
            $query1 = $conn->query($sql1);
            $row1 = $query1->fetch_assoc();
            $city_id = $row['city_id'];
            $sql2 = "SELECT * from districts_or_cities where id = '$city_id'";
            $query2 = $conn->query($sql2);
            $row2 = $query2->fetch_assoc();
            $industry_id = $row['industry_id'];
            $sql3 =  "SELECT * FROM industry WHERE id = '$industry_id'";
            $query3 = $conn->query($sql3);
            $row3 = $query3->fetch_assoc();
        ?>
        <section class="profile-card company-profile">
          <header>
            <div class="profile-pic-wrap">
              <img src="../assets/images/<?php echo htmlspecialchars($row['profile_pic'] ?: 'user.png'); ?>" alt="Profile Picture" class="profile-pic" />
              <form method="post" action="../process/changePic.php" enctype="multipart/form-data">
                <input type="file" name="picture" accept="image/*" class="file-upload">
                <button type="submit" name="companyPic" class="btn btn-secondary">Change Picture</button>
              </form>
            </div>
            <h2><?php echo htmlspecialchars($row['companyname']); ?></h2>
            <span class="profile-role">Employer</span>
          </header>
          <main>
            <form action="../process/changeProfile.php" method="post" class="profile-form">
              <div class="form-row">
                <label>Company Name</label>
                <input type="text" name="companyname" value="<?php echo htmlspecialchars($row['companyname']); ?>" required>
              </div>
              <div class="form-row">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
              </div>
              <div class="form-row">
                <label>About</label>
                <textarea name="aboutme" required><?php echo htmlspecialchars($row['aboutme']); ?></textarea>
              </div>
              <div class="form-row">
                <label>Industry</label>
                <select name="industry" required>
                  <?php
                  if ($industry_id !== null && $industry_id != 0) {
                    echo '<option value="' . $industry_id . '" selected>' . htmlspecialchars($row3['name']) . '</option>';
                  } else {
                    echo '<option value="">Select Industry</option>';
                  }
                  $industrySql = "SELECT * from industry";
                  $industryQuery = $conn->query($industrySql);
                  while ($industry = $industryQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $industry['id'] ?>"><?php echo htmlspecialchars($industry['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>Phone Number</label>
                <input type="text" name="phoneno" value="<?php echo htmlspecialchars($row['contactno']); ?>" required>
              </div>
              <div class="form-row">
                <label>Date of Establishment</label>
                <input type="date" name="esta_date" value="<?php echo htmlspecialchars($row['esta_date']); ?>" required>
              </div>
              <div class="form-row">
                <label>No. of Employees</label>
                <input type="number" name="empno" value="<?php echo htmlspecialchars($row['empno']); ?>" required>
              </div>
              <div class="form-row">
                <label>Division/State</label>
                <select name="region" required>
                  <?php
                  if ($state_id !== null && $state_id != 0) {
                    echo '<option value="' . $state_id . '" selected>' . htmlspecialchars($row1['name']) . '</option>';
                  } else {
                    echo '<option value="">Select Division/State</option>';
                  }
                  $divisionSql = "SELECT * from states";
                  $divisionQuery = $conn->query($divisionSql);
                  while ($division = $divisionQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $division['id'] ?>"><?php echo htmlspecialchars($division['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>City/District</label>
                <select name="city" required>
                  <?php
                  if ($city_id !== null && $city_id != 0) {
                    echo '<option value="' . $city_id . '" selected>' . htmlspecialchars($row2['name']) . '</option>';
                  } else {
                    echo '<option value="">Select District/City</option>';
                  }
                  $districtOrCitySql = "SELECT * from districts_or_cities";
                  $districtOrCityQuery = $conn->query($districtOrCitySql);
                  while ($districtOrCity = $districtOrCityQuery->fetch_assoc()) {
                  ?>
                    <option value="<?php echo $districtOrCity['id'] ?>"><?php echo htmlspecialchars($districtOrCity['name']) ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-row">
                <label>Office Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
              </div>
              <div class="form-row">
                <label>Website</label>
                <input type="text" name="website" value="<?php echo htmlspecialchars($row['website']); ?>" required>
              </div>
              <div class="form-row">
                <button type="submit" name="companyProfile" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
            <div class="security-section">
              <form method="post" action="../process/changePassword.php">
                <label>New Password</label>
                <input name="newPassword" type="password" minlength="6" placeholder="New Password">
                <label>Confirm Password</label>
                <input type="password" minlength="6" placeholder="Confirm New Password">
                <button type="submit" name="companyPassword" class="btn btn-secondary">Update Password</button>
              </form>
              <form action="../process/deactivate.php" method="post">
                <button type="submit" name="companyProfile" class="btn btn-danger">Deactivate Account</button>
              </form>
            </div>
          </main>
        </section>
        <?php } ?>
      <?php endif; ?>

      <!-- Profile Section: Admin -->
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3): ?>
        <?php
          $id_admin = isset($_SESSION['id_admin']) ? $_SESSION['id_admin'] : null;
          if ($id_admin) {
            $sql = "SELECT * FROM admin WHERE id_admin='$id_admin'";
            $query = $conn->query($sql);
            $row = $query->fetch_assoc();
        ?>
        <section class="profile-card admin-profile">
          <header>
            <div class="profile-pic-wrap">
              <img src="../assets/images/<?php echo htmlspecialchars($row['profile_pic'] ?: 'user.png'); ?>" alt="Profile Picture" class="profile-pic" />
              <form method="post" action="../process/changePic.php" enctype="multipart/form-data">
                <input type="file" name="picture" accept="image/*" class="file-upload">
                <button type="submit" name="aPic" class="btn btn-secondary">Change Picture</button>
              </form>
            </div>
            <h2><?php echo htmlspecialchars($row['fullname']); ?></h2>
            <span class="profile-role">Admin</span>
          </header>
          <main>
            <form action="../process/changeProfile.php" method="post" class="profile-form">
              <div class="form-row">
                <label>Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>
              </div>
              <div class="form-row">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
              </div>
              <div class="form-row">
                <label>Phone Number</label>
                <input type="text" name="phoneno" value="<?php echo htmlspecialchars($row['contactno']); ?>" required>
              </div>
              <div class="form-row">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($row['dob']); ?>" required>
              </div>
              <div class="form-row">
                <label>Gender</label>
                <input type="text" name="gender" value="<?php echo htmlspecialchars($row['gender']); ?>" required>
              </div>
              <div class="form-row">
                <label>Residential Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
              </div>
              <div class="form-row">
                <button type="submit" name="aProfile" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
            <div class="security-section">
              <form method="post" action="../process/changePassword.php">
                <label>New Password</label>
                <input name="newPassword" type="password" minlength="6" placeholder="New Password">
                <label>Confirm Password</label>
                <input type="password" minlength="6" placeholder="Confirm New Password">
                <button type="submit" name="aPassword" class="btn btn-secondary">Update Password</button>
              </form>
              <form action="../process/deactivate.php" method="post">
                <button type="submit" name="adminProfile" class="btn btn-danger">Deactivate Account</button>
              </form>
            </div>
          </main>
        </section>
        <?php } ?>
      <?php endif; ?>
    </div>
  </div>
</body>
                    <input type="text" name="website" value="<?php echo htmlspecialchars($row['website']) ?>" required>
