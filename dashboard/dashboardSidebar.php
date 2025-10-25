<aside class="sidebar">
  <div class="dashboard-profile-box">
    <?php
    $role_id = $_SESSION['role_id'] ?? null;

    if ($role_id == 1 && isset($_SESSION['user_id'])) {
        $id_user = $_SESSION['user_id'];
        $sql = "SELECT fullname FROM users WHERE id_user='$id_user'";
        $row = $conn->query($sql)->fetch_assoc();
        $displayName = $row['fullname'] ?? 'User';
    } elseif ($role_id == 2 && isset($_SESSION['id_company'])) {
        $id_company = $_SESSION['id_company'];
        $sql = "SELECT companyname FROM company WHERE id_company='$id_company'";
        $row = $conn->query($sql)->fetch_assoc();
        $displayName = $row['companyname'] ?? 'Company';
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

    <?php endif; ?>
  </ul>
</aside>
