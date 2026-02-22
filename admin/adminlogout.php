<?php
// adminlogout.php
session_start();
session_unset();     // Clear all session variables
session_destroy();   // Destroy the session
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logged Out</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #838ec9, #e593df);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logout-box {
            width: 400px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .logout-box h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .logout-box p {
            color: #555;
            margin-bottom: 25px;
        }
        .logout-box a {
            display: inline-block;
            padding: 12px 20px;
            background: #a95a97;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .logout-box a:hover {
            background: #dc78cb;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h2>You have been logged out ✅</h2>
        <p>Thank you for using the system. Click below to login again.</p>
        <a href="adminlogin.php">Go to Login</a>
    </div>
</body>
</html>