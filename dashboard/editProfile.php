<?php include "../includes/conn.php"; ?>
<?php include "../includes/indexHeader.php"; ?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>

  <div class="profile-wrapper">
    <?php include "./dashboardSidebar.php"; ?>

    <div class="profile-main">
      <!-- JOB SEEKER PROFILE -->
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 && isset($_SESSION['id_user'])) :
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT * FROM users WHERE id_user='$id_user'";
        $query = $conn->query($sql);
        $row = $query->fetch_assoc();

        // Fetch related data
        $state_id = $row['state_id'];
        $sql1 = "SELECT * FROM states WHERE id='$state_id'";
        $row1 = $conn->query($sql1)->fetch_assoc();

        $city_id = $row['city_id'];
        $sql2 = "SELECT * FROM districts_or_cities WHERE id='$city_id'";
        $row2 = $conn->query($sql2)->fetch_assoc();

        $education_id = $row['education_id'];
        $sql3 = "SELECT * FROM education WHERE id='$education_id'";
        $row3 = $conn->query($sql3)->fetch_assoc();
      ?>
        <div class="profile-section">
          <div class="profile-header">
            <h2>My Profile</h2>
            <p>Update your personal details, contact info, and more.</p>
          </div>

          <div class="profile-flex">
            <!-- LEFT SIDE: Image + Form -->
            <div class="profile-card">
              <div class="profile-photo-box">
                <img src="../assets/images/<?php echo $row['profile_pic'] ?: 'user.png'; ?>" alt="Profile Picture" class="profile-photo">
                <form action="../process/changePic.php" method="post" enctype="multipart/form-data">
                  <input type="file" name="picture" accept="image/*" class="file-input">
                  <button type="submit" class="btn btn-outline" name="myPic">Change Picture</button>
                </form>
              </div>
            </div>

            <!-- RIGHT SIDE: Profile Info -->
            <div class="profile-card wide">
              <form method="post" action="../process/changeProfile.php" class="profile-form">
                <div class="form-grid">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
                  </div>
                  <div class="form-group full">
                    <label>About</label>
                    <textarea name="aboutme" rows="4" required><?php echo $row['aboutme']; ?></textarea>
                  </div>
                  <div class="form-group">
                    <label>Headline</label>
                    <input type="text" name="headline" value="<?php echo $row['headline']; ?>">
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phoneno" value="<?php echo $row['contactno']; ?>">
                  </div>
                  <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?php echo $row['dob']; ?>">
                  </div>
                  <div class="form-group">
                    <label>Gender</label>
                    <input type="text" name="gender" value="<?php echo $row['gender']; ?>">
                  </div>

                  <div class="form-group">
                    <label>Division/State</label>
                    <select name="region" required>
                      <option value="<?php echo $state_id; ?>" selected><?php echo $row1['name']; ?></option>
                      <?php
                      $result = $conn->query("SELECT * FROM states");
                      while ($opt = $result->fetch_assoc()) {
                        echo "<option value='{$opt['id']}'>{$opt['name']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>City/District</label>
                    <select name="city" required>
                      <option value="<?php echo $city_id; ?>" selected><?php echo $row2['name']; ?></option>
                      <?php
                      $cities = $conn->query("SELECT * FROM districts_or_cities");
                      while ($opt = $cities->fetch_assoc()) {
                        echo "<option value='{$opt['id']}'>{$opt['name']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group full">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo $row['address']; ?>" required>
                  </div>

                  <div class="form-group">
                    <label>Highest Education</label>
                    <select name="education">
                      <option value="<?php echo $education_id; ?>" selected><?php echo $row3['name']; ?></option>
                      <?php
                      $edu = $conn->query("SELECT * FROM education");
                      while ($opt = $edu->fetch_assoc()) {
                        echo "<option value='{$opt['id']}'>{$opt['name']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group full">
                    <label>Skills</label>
                    <textarea name="skills" rows="3" required><?php echo $row['skills']; ?></textarea>
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" name="myProfile" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Password + Deactivate -->
          <div class="profile-bottom">
            <div class="profile-card half">
              <h3>Change Password</h3>
              <form action="../process/changePassword.php" method="post">
                <input type="password" name="newPassword" placeholder="New Password" minlength="6" required>
                <input type="password" placeholder="Confirm Password" minlength="6" required>
                <button type="submit" name="myPassword" class="btn btn-outline">Update Password</button>
              </form>
            </div>

            <div class="profile-card half">
              <h3>Deactivate Account</h3>
              <form action="../process/deactivate.php" method="post">
                <button type="submit" name="userProfile" class="btn btn-danger">Deactivate</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
