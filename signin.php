<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CareerConnectDB</title>
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
        input[type="email"], input[type="password"] {
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
        <h2>Sign In</h2>
        <?php
        session_start();
        include './includes/conn.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Using alias, AS, and LIMIT 
            $sql_user = "SELECT 
                            u.id_user AS user_id, 
                            u.password AS user_password, 
                            u.fullname AS user_fullname, 
                            u.role_id AS user_role 
                         FROM users u 
                         WHERE u.email = ? 
                         AND u.active = 1 
                         LIMIT 1";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("s", $email);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();

            // Using alias and column 
            $sql_company = "SELECT 
                                c.id_company AS company_id, 
                                c.password AS company_password, 
                                c.companyname AS company_name, 
                                c.role_id AS company_role 
                            FROM company c 
                            WHERE c.email = ? 
                            AND c.active = 1 
                            LIMIT 1";
            $stmt_company = $conn->prepare($sql_company);
            $stmt_company->bind_param("s", $email);
            $stmt_company->execute();
            $result_company = $stmt_company->get_result();

            $logged_in = false;

            if ($result_user->num_rows > 0) {
                $row = $result_user->fetch_assoc();
                if (password_verify($password, $row['user_password'])) {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['fullname'] = $row['user_fullname'];
                    $_SESSION['role_id'] = $row['user_role'];
                    $_SESSION['email'] = $email;
                    $logged_in = true;
                }
            } elseif ($result_company->num_rows > 0) {
                $row = $result_company->fetch_assoc();
                if (password_verify($password, $row['company_password'])) {
                    $_SESSION['id_company'] = $row['company_id'];
                    $_SESSION['user_id'] = $row['company_id'];
                    $_SESSION['fullname'] = $row['company_name'];
                    $_SESSION['role_id'] = $row['company_role'];
                    $_SESSION['email'] = $email;
                    $logged_in = true;
                }
            }

            if ($logged_in) {
                header("Location: index.php");
                exit();
            } else {
                echo "<p style='color: red;'>Invalid email or password.</p>";
            }

            $stmt_user->close();
            $stmt_company->close();
        }
        $conn->close();
        ?>
        <form action="signin.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>
        <div class="link">
            <a href="signup.php">Don't have an account? Sign Up</a>
        </div>
    </div>
    <?php include './includes/sql_terminal.php'; ?>
</body>
</html>
