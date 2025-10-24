<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/conn.php";
include "../includes/indexHeader.php";
?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="message-container <?php echo $_SESSION['messagetype']; ?>">
        <div class="message-content">
          <i class="fa-solid fa-<?php echo $_SESSION['messagetype'] == 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
          <span><?php echo $_SESSION['message']; ?></span>
        </div>
        <button class="message-close" onclick="this.parentElement.style.display='none'">
          <i class="fa-solid fa-times"></i>
        </button>
      </div>
      <?php unset($_SESSION['message']); unset($_SESSION['messagetype']); ?>
    <?php endif; ?>

    <div class="resume-content-container">
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1 && isset($_SESSION['user_id'])) :
        $id_user = mysqli_real_escape_string($conn, $_SESSION['user_id']);
        $sql = "SELECT resume FROM users WHERE id_user = '$id_user'";
        $query = $conn->query($sql);
        $row = $query->fetch_assoc();
      ?>
        <div class="resume-content-container-left-side">
          <div class="headline">
            <h3>My Resume</h3>
          </div>
          <div class="resume-container">
            <?php if (empty($row['resume'])) : ?>
              <div class="no-resume-message">
                <i class="fa-solid fa-file-pdf"></i>
                <p>No Resume Uploaded</p>
                <span>Please upload your resume to apply for jobs</span>
              </div>
            <?php else : ?>
              <div class="resume-display">
                <i class="fa-solid fa-file-pdf"></i>
                <div class="resume-info">
                  <h4>Current Resume</h4>
                  <p><?php echo htmlspecialchars(basename($row['resume'])); ?></p>
                </div>
                <a href="../assets/resumes/<?php echo htmlspecialchars($row['resume']); ?>" target="_blank" class="btn btn-secondary">
                  <i class="fa-solid fa-download"></i> Download Resume
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="resume-content-container-right-side">
          <div class="headline">
            <h3>Upload New Resume</h3>
          </div>
          <div class="upload-resume-container">
            <div class="upload-icon">
              <img src="../assets/images/pdf.png" alt="PDF Upload" />
            </div>
            <div class="upload-form">
              <p>Upload your resume in PDF format</p>
              <form method="POST" action="../process/changeResume.php" enctype="multipart/form-data">
                <div class="file-input-wrapper">
                  <input name="resume" type="file" accept=".pdf" required="" id="resume-file">
                  <label for="resume-file" class="file-label">
                    <i class="fa-solid fa-cloud-upload-alt"></i>
                    Choose PDF File
                  </label>
                  <span class="file-name" id="file-name">No file chosen</span>
                </div>
                <button type="submit" class="btn btn-primary" name="changeResume">
                  <i class="fa-solid fa-upload"></i> Upload Resume
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // File name display
    document.getElementById('resume-file').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
      document.getElementById('file-name').textContent = fileName;
    });
  </script>
</body>