<?php
// adminlogin.php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "doctorappointmentweb";  

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch admin from DB (check both email and username)
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? AND name = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Verify password (stored as hash in DB)
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id']    = $row['id'];
            $_SESSION['admin_name']  = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            header("Location: admindashboard.php"); // redirect after login
            exit();
        } else {
            $message = "❌ Wrong password!";
        }
    } else {
        $message = "❌ No such admin!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #c47dbe, #a36fb2); /* Orange gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 350px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        .login-box input[type=text],
        .login-box input[type=email],
        .login-box input[type=password] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        .login-box input[type=submit] {
            width: 100%;
            padding: 12px;
            background: #a1abea;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            transition: background 0.3s ease;
        }
        .login-box input[type=submit]:hover {
            background: #89b1cf;
        }
        .message {
            text-align: center;
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" value="Login">
        </form>
        <?php if ($message != ""): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>