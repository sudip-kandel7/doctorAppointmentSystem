<?php
include("../db_connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if patient exists
    $stmt = $mysqli->prepare("SELECT id FROM patient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if ($patient) {
        // Generate token and expiry
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token to database
        $update = $mysqli->prepare("UPDATE patient SET reset_token=?, reset_token_expiry=? WHERE id=?");
        $update->bind_param("ssi", $token, $expiry, $patient['id']);
        $update->execute();

        // Show reset link (you can email this later)
        $resetLink = "http://localhost/doctorappointmentweb/patient/patientresetpassword.php?token=$token";
        $message = "Password reset link: <a href='$resetLink'>$resetLink</a>";
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .form-box { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input[type=email] {
            width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px;
        }
        input[type=submit] {
            background-color: #e67e22; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%;
            font-weight: bold; cursor: pointer;
        }
        input[type=submit]:hover { background-color: #d35400; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .message { color: green; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Forgot Password</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
</body>
</html>