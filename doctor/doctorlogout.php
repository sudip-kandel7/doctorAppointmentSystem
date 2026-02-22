<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Logout</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f4f6f9; text-align:center; padding-top:100px; }
        .message-box { background:white; display:inline-block; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        h2 { color:#2c3e50; margin-bottom:20px; }
        p { color:#555; margin-bottom:20px; }
        a { display:inline-block; background:#3498db; color:white; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold; }
        a:hover { background:#2980b9; }
    </style>
</head>
<body>
    <div class="message-box">
        <h2>Logout Successful ✅</h2>
        <p>You have been logged out of your doctor account.</p>
        <!-- Link back to doctor login page -->
        <a href="doctorlogin.php">Go to Login Page</a>
    </div>
</body>
</html>