<?php include "../includes/conn.php"; ?>
<?php include "../includes/indexHeader.php"; ?>

<body>
  <?php include "../includes/indexNavbar.php"; ?>
  <div class="dashboard-container">
    <?php include "./dashboardSidebar.php"; ?>
    <div class="all-jobseekers-container">
      <div class="headline headline-container">
        <h3>All Job Seekers</h3>
      </div>
      <div>
        <table>
          <thead>
            <th>#</th>
            <th>Profile Pic</th>
            <th>Fullname</th>
            <th>Gender</th>
            <th>Telephone</th>
            <th>Education</th>
            <th>Division</th>
            <th>City</th>
            <th>Address</th>
            <th>Action</th>
          </thead>
          <tbody>
            <?php
            // ✅ Use LEFT JOIN to fetch education, state, and city names in one go
            $sql = "
              SELECT 
                u.id_user,
                u.fullname,
                u.gender,
                u.contactno,
                u.address,
                u.profile_pic,
                COALESCE(e.name, 'N/A') AS education_name,
                COALESCE(s.name, 'N/A') AS state_name,
                COALESCE(c.name, 'N/A') AS city_name
              FROM users u
              LEFT JOIN education e ON u.education_id = e.id
              LEFT JOIN states s ON u.state_id = s.id
              LEFT JOIN districts_or_cities c ON u.city_id = c.id
              ORDER BY u.id_user DESC
            ";

            $query = $conn->query($sql);
            $i = 1;

            while ($row = $query->fetch_assoc()) {
              $hash = md5($row['id_user']);
              $id_user = $row['id_user'];

              echo "
                <tr>
                  <td>{$i}</td>
                  <td><img height='50' width='50' src='../assets/images/{$row['profile_pic']}'></td>
                  <td>{$row['fullname']}</td>
                  <td>" . (!empty($row['gender']) ? $row['gender'] : 'unknown') . "</td>
                  <td>" . (!empty($row['contactno']) ? $row['contactno'] : 'unknown') . "</td>
                  <td>{$row['education_name']}</td>
                  <td>{$row['state_name']}</td>
                  <td>{$row['city_name']}</td>
                  <td>" . (!empty($row['address']) ? $row['address'] : 'unknown') . "</td>
                  <td class='action-button'>
                    <a href='../process/deleteUsers.php?key={$hash}&id={$id_user}&page=delete-jobseekers' class='btn btn-optional'>Remove</a> 
                  </td>
                </tr>
              ";
              $i++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php include '../includes/sql_terminal.php'; ?>
</body>
</html>
