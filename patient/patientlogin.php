<?php
include("../db_connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Look up patient by email
    $stmt = $mysqli->prepare("SELECT id, password FROM patient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if ($patient && password_verify($password, $patient['password'])) {
        // Login success → set session and redirect
        $_SESSION['patient_id'] = $patient['id'];
        header("Location: patientdashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .form-box { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type=email], input[type=password] {
            width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type=submit] {
            background-color: #3498db; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;
            font-weight: bold; cursor: pointer;
        }
        input[type=submit]:hover { background-color: #2980b9; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .links { text-align: center; margin-top: 15px; }
        .links a { color: #27ae60; text-decoration: none; font-weight: bold; margin: 0 10px; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Patient Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <div class="links">
            <p>Haven't registered? <a href="patientregister.php">Register here</a></p>
            <p><a href="patientforgotpassword.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>