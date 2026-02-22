<?php
session_start();
include('db_connect.php'); // uses $conn

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
$doctor = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Count unread notifications for doctor
$stmt = $conn->prepare("SELECT COUNT(*) AS unread_count
                        FROM notifications n
                        JOIN appointment a ON n.appointment_id = a.id
                        WHERE a.doctor_id=? AND n.user_type='doctor' AND n.seen=0");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($unread_count);
$stmt->fetch();
$stmt->close();

// Fetch notifications list
$stmt = $conn->prepare("SELECT n.message, n.created_at, n.seen
                        FROM notifications n
                        JOIN appointment a ON n.appointment_id = a.id
                        WHERE a.doctor_id=? AND n.user_type='doctor'
                        ORDER BY n.created_at DESC");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$notifications = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial; background:#f4f6f9; margin:0; }
        .navbar { background:#3498db; padding:15px; text-align:center; }
        .navbar a { color:white; text-decoration:none; margin:0 15px; font-weight:bold; position:relative; }
        .navbar a:hover { text-decoration:underline; }
        .badge {
            background:red; color:white; border-radius:50%;
            padding:3px 7px; font-size:12px; position:absolute; top:-8px; right:-12px;
        }
        .section { background:#fff; padding:20px; margin:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        .profile img { width:120px; height:120px; border-radius:50%; margin-bottom:15px; object-fit:cover; }
        .note { background:#fff; padding:15px; margin:10px 0; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        .note.unread { border-left:5px solid #e67e22; }
        .time { font-size:12px; color:#888; }
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
        <a href="doctornotifications.php">Notifications 
            <?php if($unread_count > 0): ?>
                <span class="badge"><?= $unread_count ?></span>
            <?php endif; ?>
        </a>
    </div>

    <!-- Doctor Profile -->
    <div class="section profile">
        <?php if(!empty($doctor['profile_pic'])): ?>
            <img src="<?= htmlspecialchars($doctor['profile_pic']) ?>" alt="Doctor Profile">
        <?php endif; ?>
        <h2>Welcome, Dr. <?= htmlspecialchars($doctor['name']) ?> 👋</h2>
        <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($doctor['experience']) ?> years</p>
        <p><strong>Available Days:</strong> <?= htmlspecialchars($doctor['available_days']) ?></p>
        <p><strong>Available Time:</strong> <?= htmlspecialchars($doctor['available_from']) ?> - <?= htmlspecialchars($doctor['available_to']) ?></p>
    </div>

    <!-- Notifications Section -->
    <div class="section">
        <h2>Notifications</h2>
        <?php while($note = $notifications->fetch_assoc()): ?>
            <div class="note <?= $note['seen']==0 ? 'unread':'' ?>">
                <p><?= htmlspecialchars($note['message']) ?></p>
                <p class="time"><?= $note['created_at'] ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>