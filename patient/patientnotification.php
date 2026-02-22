<?php
session_start();
include('db_connect.php');

if(!isset($_SESSION['patient_id'])){
    header("Location: patientlogin.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch notifications for this patient
$stmt = $conn->prepare("SELECT message, created_at, seen 
                        FROM notifications 
                        JOIN appointment a ON notifications.appointment_id = a.id
                        WHERE a.patient_id=? AND notifications.user_type='patient'
                        ORDER BY created_at DESC");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Notifications</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:20px; }
        h2 { color:#2c3e50; }
        .note { background:#fff; padding:15px; margin:10px 0; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        .note.unread { border-left:5px solid #3498db; }
        .time { font-size:12px; color:#888; }
    </style>
</head>
<body>
    <h2>Notifications</h2>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="note <?= $row['seen']==0 ? 'unread':'' ?>">
            <p><?= htmlspecialchars($row['message']) ?></p>
            <p class="time"><?= $row['created_at'] ?></p>
        </div>
    <?php endwhile; ?>
</body>
</html>