<?php
session_start();
include('db_connect.php');

if(!isset($_SESSION['doctor_id'])){
    header("Location: doctorlogin.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch notifications for doctor
$stmt = $conn->prepare("SELECT n.id, n.message, n.created_at, n.seen
                        FROM notifications n
                        JOIN appointment a ON n.appointment_id = a.id
                        WHERE a.doctor_id=? AND n.user_type='doctor'
                        ORDER BY n.created_at DESC");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$notifications = $stmt->get_result();
$stmt->close();

// Mark all as read once doctor views this page
$stmt = $conn->prepare("UPDATE notifications n
                        JOIN appointment a ON n.appointment_id = a.id
                        SET n.seen=1
                        WHERE a.doctor_id=? AND n.user_type='doctor'");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Notifications</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:20px; }
        h2 { color:#2c3e50; }
        .note { background:#fff; padding:15px; margin:10px 0; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        .note.unread { border-left:5px solid #e67e22; }
        .time { font-size:12px; color:#888; }
    </style>
</head>
<body>
    <h2>Notifications</h2>
    <?php while($note = $notifications->fetch_assoc()): ?>
        <div class="note <?= $note['seen']==0 ? 'unread':'' ?>">
            <p><?= htmlspecialchars($note['message']) ?></p>
            <p class="time"><?= $note['created_at'] ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>