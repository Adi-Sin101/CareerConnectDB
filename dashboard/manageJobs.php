<?php include "../includes/conn.php"; ?>
<?php include "../includes/indexHeader.php" ?>

<style>
    :root {
        --primary: #1B3C53;
        --secondary: #456882;
        --accent: #D2C1B6;
        --light: #F9F3EF;
    }
    .manage-job-content-container {
        background: var(--light);
        color: var(--primary);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        height: 100%;
        box-sizing: border-box;
    }
    .manage-job-content-container h3 {
        text-align: center;
        margin-bottom: 20px;
    }
    .job-item-container {
        background: #fff;
        border: 1px solid var(--accent);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-container img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }
    .job-info-container {
        flex: 1;
    }
    .job-info-upper-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .validity-active {
        background: var(--accent);
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8em;
    }
    .activity-container a {
        margin-left: 10px;
        color: var(--primary);
        text-decoration: none;
    }
    .activity-container a:hover {
        color: var(--secondary);
    }
    .title-with-job-status h3 {
        margin: 0;
        color: var(--primary);
    }
    .job-status, .job-category-info, .date-info, .salary-info, .location-info {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 5px 0;
        color: var(--secondary);
    }
</style>

<body>
<?php include "../includes/indexNavbar.php" ?>
<div class="dashboard-main-theme">
    <div class="dashboard-container">
        <?php include "./dashboardSidebar.php" ?>
        <div class="dashboard-content-container themed-content">
            <div class="manage-job-content-container">
                <div class="headline">
                    <h3>My Jobs Listings</h3>
                </div>
                <?php
                $id_company = isset($_SESSION['id_company']) ? $_SESSION['id_company'] : null;

                if ($id_company) {
                    // Single JOIN query to fetch all job details with related info
                    $sql = "
                        SELECT jp.*, d.name AS city_name, i.name AS industry_name, jt.type AS job_type, c.profile_pic 
                        FROM job_post jp
                        LEFT JOIN districts_or_cities d ON jp.city_id = d.id
                        LEFT JOIN industry i ON jp.industry_id = i.id
                        LEFT JOIN job_type jt ON jp.job_status = jt.id
                        LEFT JOIN company c ON jp.id_company = c.id_company
                        WHERE jp.id_company = '$id_company'
                        ORDER BY jp.id_jobpost DESC
                    ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id_jobpost = $row['id_jobpost'];
                            $jobtitle = $row['jobtitle'];
                            $job_type = $row['job_type'];
                            $industry_name = $row['industry_name'];
                            $city_name = $row['city_name'];
                            $minimum_salary = $row['minimumsalary'];
                            $maximum_salary = $row['maximumsalary'];
                            $create_date = $row['createdat'];
                            $profile_pic = $row['profile_pic'];
                            $hash = md5($id_jobpost);
                ?>
                            <div class="job-item-container">
                                <div class="profile-container">
                                    <img src="../assets/images/<?php echo $profile_pic ?>" alt="">
                                </div>
                                <div class="job-info-container">
                                    <div class="job-info-upper-area">
                                        <span class="validity-active">Active</span>
                                        <div class="activity-container">
                                            <a href="../jobDetails.php?key=<?php echo $hash . '&id=' . $id_jobpost ?>"><i class="fa-solid fa-eye"></i></a>
                                            <a href="./editJob.php?key=<?php echo $hash . '&id=' . $id_jobpost ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="../process/deleteJobs.php?key=<?php echo $hash . '&id=' . $id_jobpost ?>"><i class="fa-solid fa-trash"></i></a>
                                        </div>
                                    </div>
                                    <div class="title-with-job-status">
                                        <h3> <?php echo $jobtitle; ?> </h3>
                                        <div class="job-status">
                                            <i class="fa-solid fa-briefcase"></i>
                                            <span><?php echo $job_type ?></span>
                                        </div>
                                    </div>
                                    <div class="others-info">
                                        <div class="job-category-info">
                                            <i class="fa-solid fa-briefcase"></i>
                                            <span><?php echo $industry_name ?></span>
                                        </div>
                                        <div class="date-info">
                                            <i class="fa-solid fa-calendar-days"></i>
                                            <span><?php echo $create_date; ?></span>
                                        </div>
                                        <div class="salary-info">
                                            <i class="fa-solid fa-money-check-dollar"></i>
                                            <span><?php echo $minimum_salary . "-" . $maximum_salary ?></span>
                                        </div>
                                        <div class="location-info">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <span><?php echo $city_name ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                <?php
                        }
                    } else {
                        echo "<p>No jobs posted yet.</p>";
                    }
                } else {
                    echo "<p>Company ID not found in session.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include '../includes/sql_terminal.php'; ?>
</body>
