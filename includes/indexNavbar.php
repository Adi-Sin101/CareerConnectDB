<nav class="navbar">
  <div class="navbar-container">
    <a href="/CareerConnectDB/index.php" class="navbar-logo">CareerConnectDB</a>

    <!-- Hamburger for mobile -->
    <div class="menu-toggle" id="mobile-menu">
      <i class="fa-solid fa-bars"></i>
    </div>

    <ul class="navbar-menu">
      <li><a href="/CareerConnectDB/index.php" class="navbar-link">Home</a></li>
      <li><a href="/CareerConnectDB/findJobs.php" class="navbar-link">Find Jobs</a></li>
      <li><a href="/CareerConnectDB/browseCompanies.php" class="navbar-link">Browse Companies</a></li>
      <li><a href="/CareerConnectDB/dashboard/dashboard.php" class="navbar-link">Profile</a></li>
    </ul>

    <div class="header-right">
      <?php
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }
      if (isset($_SESSION['email'])) {
        $username = explode('@', $_SESSION['email'])[0];
        echo '<div class="dropdown">';
        echo '<button class="dropdown-btn">Hi, ' . htmlspecialchars($username) . ' <i class="fa-solid fa-chevron-down"></i></button>';
        echo '<div class="dropdown-content">';
        echo '<a href="/CareerConnectDB/dashboard/dashboard.php"><i class="fa-solid fa-table-cells-large"></i> Dashboard</a>';
        echo '<a href="/CareerConnectDB/dashboard/editProfile.php"><i class="fa-solid fa-user"></i> My Profile</a>';
        echo '<a href="/CareerConnectDB/process/logout.php"><i class="fa-solid fa-power-off"></i> Logout</a>';
        echo '</div>';
        echo '</div>';
      } else {
        echo '<a href="/CareerConnectDB/signin.php" class="btn btn-outline">Sign In</a>';
        echo '<a href="/CareerConnectDB/signup.php" class="btn btn-primary">Sign Up</a>';
      }
      ?>
    </div>
  </div>
</nav>

<script>
  // Mobile menu toggle
  document.getElementById("mobile-menu").addEventListener("click", () => {
    document.querySelector(".navbar-menu").classList.toggle("active");
  });
</script>
