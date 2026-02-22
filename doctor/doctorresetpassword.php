<?php
include("../db_connection.php");
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $mysqli->prepare("SELECT id, reset_token_expiry FROM doctor WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();

    if ($doctor && strtotime($doctor['reset_token_expiry']) > time()) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $update = $mysqli->prepare("UPDATE doctor SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE id=?");
            $update->bind_param("si", $newPassword, $doctor['id']);
            $update->execute();

            $success = "Password has been reset successfully. <a href='doctorlogin.php'>Login here</a>";
        }
    } else {
        $error = "Invalid or expired token.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .form-box { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type=password] { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; }
        input[type=submit] { background-color: #27ae60; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%; font-weight: bold; cursor: pointer; }
        input[type=submit]:hover { background-color: #1e8449; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .success { color: green; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Reset Password</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (!isset($success) && !isset($error)) { ?>