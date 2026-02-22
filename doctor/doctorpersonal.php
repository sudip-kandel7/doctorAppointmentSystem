<?php
session_start();
include('db_connect.php'); // uses $conn

// Ensure doctor is logged in
if(!isset($_SESSION['doctor_id'])){
    header("Location: doctorlogin.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor details
$stmt = $conn->prepare("SELECT name, specialization, email, experience, available_days, available_from, available_to, profile_pic 
                        FROM doctor WHERE id=?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Personal Details</title>
    <style>
        body { font-family: 'Segoe UI', Arial; background:#f4f6f9; margin:0; }
        .navbar { background:#3498db; padding:15px; text-align:center; }
        .navbar a { color:white; text-decoration:none; margin:0 15px; font-weight:bold; }
        .navbar a:hover { text-decoration:underline; }
        .profile { background:#fff; padding:20px; margin:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; }
        .profile img { width:120px; height:120px; border-radius:50%; margin-bottom:15px; object-fit:cover; }
        .profile h2 { margin:0; color:#2c3e50; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="doctordashboard.php">Dashboard</a>
        <a href="doctorappointment.php">Appointments</a>
        <a href="doctorhistory.php">History</a>
        <a href="doctorpersonal.php">Personal Details</a>
        <a href="doctorlogout.php">Logout</a>
    </div>

    <!-- Doctor Personal Info -->
    <div class="profile">
        <?php if($doctor): ?>
            <?php if(!empty($doctor['profile_pic'])): ?>
                <img src="<?= htmlspecialchars($doctor['profile_pic']) ?>" alt="Doctor Profile">
            <?php endif; ?>
            <h2>Welcome, Dr. <?= htmlspecialchars($doctor['name']) ?> 👋</h2>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
            <p><strong>Experience:</strong> <?= htmlspecialchars($doctor['experience']) ?> years</p>
            <p><strong>Available Days:</strong> <?= htmlspecialchars($doctor['available_days']) ?></p>
            <p><strong>Available Time:</strong> <?= htmlspecialchars($doctor['available_from']) ?> - <?= htmlspecialchars($doctor['available_to']) ?></p>
        <?php else: ?>
            <p style="color:red;">No doctor details found. Please check your database record or login session.</p>
        <?php endif; ?>
    </div>
</body>
</html>