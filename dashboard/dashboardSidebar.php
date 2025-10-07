<aside class="sidebar">
  <div class="dashboard-profile-box">
    <?php
    $role_id = $_SESSION['role_id'] ?? null;

    if ($role_id == 1 && isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT fullname FROM users WHERE id_user='$id_user'";
        $row = $conn->query($sql)->fetch_assoc();
        $displayName = $row['fullname'] ?? 'User';
    } elseif ($role_id == 2 && isset($_SESSION['id_company'])) {
        $id_company = $_SESSION['id_company'];
        $sql = "SELECT companyname FROM company WHERE id_company='$id_company'";
        $row = $conn->query($sql)->fetch_assoc();
        $displayName = $row['companyname'] ?? 'Company';
    } elseif ($role_id == 3 && isset($_SESSION['id_admin'])) {
        $id_admin = $_SESSION['id_admin'];
        $sql = "SELECT fullname FROM admin WHERE id_admin='$id_admin'";
        $row = $conn->query($sql)->fetch_assoc();
        $displayName = $row['fullname'] ?? 'Admin';
    } else {
        $displayName = 'Guest';
    }
    ?>
    <div class="user-profile-text">
      <span class="fullname"><?= htmlspecialchars($displayName) ?></span>
    </div>
  </div>

  <ul>
    <?php if ($role_id == 1): ?>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="myResume.php">Manage Resume</a></li>
      <li><a href="editProfile.php">Edit Profile</a></li>
      <li><a href="../process/logout.php">Logout</a></li>

    <?php elseif ($role_id == 2): ?>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="./addJob.php">Add New Job</a></li>
      <li><a href="manageJobs.php">Manage Jobs</a></li>
      <li><a href="./manageApplications.php">Manage Applications</a></li>
      <li><a href="editProfile.php">Edit Profile</a></li>
      <li><a href="../process/logout.php">Logout</a></li>

    <?php elseif ($role_id == 3): ?>
      <?php
        // Pre-fetch counts for admin badges
        $jobCount = $conn->query("SELECT COUNT(*) as c FROM job_post")->fetch_assoc()['c'];
        $companyCount = $conn->query("SELECT COUNT(*) as c FROM company")->fetch_assoc()['c'];
        $userCount = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
        $adminCount = $conn->query("SELECT COUNT(*) as c FROM admin")->fetch_assoc()['c'];
      ?>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="./allJobPost.php">All Job Posts <span class="badge"><?= $jobCount ?></span></a></li>
      <li><a href="./allCompanies.php">All Companies <span class="badge"><?= $companyCount ?></span></a></li>
      <li><a href="./allJobSeekers.php">All Job Seekers <span class="badge"><?= $userCount ?></span></a></li>
      <li><a href="./adminUsers.php">Users - Admin <span class="badge"><?= $adminCount ?></span></a></li>
      <li><a href="editProfile.php">Edit Profile</a></li>
      <li><a href="../process/logout.php">Logout</a></li>
    <?php endif; ?>
  </ul>
</aside>
