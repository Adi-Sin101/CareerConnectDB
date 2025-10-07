<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CareerConnectDB - Job Portal</title>

  <!-- FontAwesome & CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/styles.css">

  <style>
    :root {
      --primary: #1B3C53;
      --secondary: #456882;
      --accent: #D2C1B6;
      --light: #F9F3EF;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: var(--light);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Hero Section */
    .main-welcome-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      padding: 80px 20px;
      text-align: center;
    }

    .welcome-card {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.25);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      border-radius: 20px;
      padding: 60px 40px;
      max-width: 700px;
      backdrop-filter: blur(10px);
      color: var(--light);
    }

    .welcome-title {
      font-size: 2.6em;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--light);
    }

    .welcome-line {
      width: 100px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
      margin: 0 auto 25px auto;
    }

    .welcome-desc {
      font-size: 1.15em;
      line-height: 1.7em;
      color: #f1e9e4;
      margin-bottom: 40px;
    }

    .btn-accent {
      background: var(--accent);
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      padding: 14px 34px;
      border-radius: 30px;
      transition: all 0.3s ease;
      font-size: 1.1em;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-accent:hover {
      background: var(--light);
      color: var(--primary);
      transform: translateY(-2px);
    }

    /* Footer (Optional, simple) */
    footer {
      padding: 20px 0;
      text-align: center;
      color: var(--light);
      font-size: 0.9em;
      background: rgba(0, 0, 0, 0.15);
      width: 100%;
    }

    @media (max-width: 768px) {
      .welcome-card {
        padding: 40px 25px;
      }

      .welcome-title {
        font-size: 2em;
      }

      .btn-accent {
        padding: 12px 26px;
        font-size: 1em;
      }
    }
  </style>
</head>

<body>
  <!-- Modern Navbar -->
  <?php include './includes/indexNavbar.php'; ?>

  <!-- Hero Section -->
  <div class="main-welcome-container">
    <div class="welcome-card">
      <h1 class="welcome-title">Welcome to CareerConnectDB</h1>
      <div class="welcome-line"></div>
      <p class="welcome-desc">
        Find your dream job and connect with top employers. Start your career journey today.
      </p>
      <a href="findJobs.php" class="btn-accent">Browse Job Listings</a>
    </div>
  </div>

  <footer>
    © <?php echo date("Y"); ?> CareerConnectDB. All rights reserved.
  </footer>
</body>
</html>
