<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CareerConnectDB</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1B3C53;
            --secondary: #456882;
            --accent: #D2C1B6;
            --light: #F9F3EF;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: var(--light);
            color: var(--primary);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background: var(--accent);
            color: var(--primary);
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: var(--light);
            border: 1px solid var(--accent);
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: var(--primary);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php
        session_start();
        include './includes/conn.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $role = $_POST['role'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo "<p style='color: red;'>Passwords do not match.</p>";
            } else {
                // Subquery to check email existence in both tables using union ---
                $sql_check = "
                    SELECT email FROM (
                        SELECT email FROM users
                        UNION ALL
                        SELECT email FROM company
                    ) AS combined_emails
                    WHERE email = ?
                ";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->bind_param("s", $email);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();

                if ($result_check->num_rows > 0) {
                    echo "<p style='color: red;'>Email already registered.</p>";
                } else {
                    
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $createdat = date("Y-m-d");

                    //Insert with explicit column names & constraint demonstration ---
                    if ($role == 'job_seeker') {
                        $sql_insert = "
                            INSERT INTO users (fullname, email, password, role_id, active, createdat)
                            VALUES (?, ?, ?, 1, 1, ?)
                        ";
                    } elseif ($role == 'employer') {
                        $sql_insert = "
                            INSERT INTO company (companyname, email, password, role_id, createdAt, active)
                            VALUES (?, ?, ?, 2, ?, 1)
                        ";
                    }

                    $stmt_insert = $conn->prepare($sql_insert);
                    $stmt_insert->bind_param("ssss", $name, $email, $hashed_password, $createdat);

                    if ($stmt_insert->execute()) {
                        echo "<p style='color: green;'>Registration successful! <a href='signin.php'>Sign In</a></p>";
                    } else {
                        echo "<p style='color: red;'>Error: " . $stmt_insert->error . "</p>";
                    }
                    $stmt_insert->close();
                }
                $stmt_check->close();
            }
        }
        $conn->close();
        ?>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="role">Sign up as:</label><br>
                <input type="radio" id="job_seeker" name="role" value="job_seeker" checked>
                <label for="job_seeker">Job Seeker</label><br>
                <input type="radio" id="employer" name="role" value="employer">
                <label for="employer">Employer</label>
            </div>
            <div class="form-group">
                <label for="name">Full Name / Company Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="link">
            <a href="signin.php">Already have an account? Sign In</a>
        </div>
    </div>
    <?php include './includes/sql_terminal.php'; ?>
</body>
</html>
