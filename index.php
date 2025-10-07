<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerConnectDB - Job Portal</title>
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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .hero {
            max-width: 800px;
            padding: 20px;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .btn {
            padding: 15px 30px;
            background: var(--accent);
            color: var(--primary);
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn:hover {
            background: var(--light);
        }
        .options {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 50px;
            flex-wrap: wrap;
        }
        .option {
            background: var(--light);
            color: var(--primary);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .option h2 {
            margin-bottom: 15px;
        }
        .option p {
            margin-bottom: 20px;
        }
        .option .btn {
            display: block;
            width: 80%;
            min-width: 120px;
            margin: 10px auto;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to CareerConnectDB</h1>
        <p>Find your dream job and connect with top employers. Start your career journey today.</p>
        <div class="buttons">
            <a href="signin.php" class="btn signin">Sign In</a>
            <a href="signup.php" class="btn signup">Sign Up</a>
        </div>
    </div>
    <div class="options">
        <div class="option">
            <h2>For Job Seekers</h2>
            <p>Upload your resume and find the perfect job.</p>
            <a href="#" class="btn">Upload Resume</a>
            <a href="#" class="btn">Find Jobs</a>
        </div>
        <div class="option">
            <h2>For Employers</h2>
            <p>Post jobs and find the ideal candidates.</p>
            <a href="#" class="btn">Post a Job</a>
            <a href="#" class="btn">Find Candidates</a>
        </div>
    </div>
</body>
</html>