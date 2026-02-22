<?php
include("../db_connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Check if patient exists
    $stmt = $mysqli->prepare("SELECT id FROM patient WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if ($patient) {
        // Generate token + expiry
        $token  = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token
        $update = $mysqli->prepare("UPDATE patient SET reset_token=?, reset_token_expiry=? WHERE id=?");
        $update->bind_param("ssi", $token, $expiry, $patient['id']);
        $update->execute();

        // Reset link (later send via email)
        $resetLink = "http://localhost/doctorappointmentweb/patient/patientresetpassword.php?token=$token";
        $message   = "Password reset link: <a href='$resetLink'>$resetLink</a>";
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f7;
            margin: 0;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }
        input[type=email], input[type=submit] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input[type=submit] {
            background: #0077cc;
            color: #fff;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        input[type=submit]:hover {
            background: #005fa3;
        }
        .error, .message {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .error { color: #d9534f; }
        .message { color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <?php if (isset($error))   echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
</body>
</html>