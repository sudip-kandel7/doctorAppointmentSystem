<?php
session_start();
include('db_connect.php');

if(!isset($_SESSION['doctor_id'])){
    header("Location: doctorlogin.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch completed appointments
$stmt = $conn->prepare("SELECT a.id, p.name AS patient_name, p.email AS patient_email,
                               a.appointment_date, a.appointment_time, a.description, a.status
                        FROM appointment a
                        JOIN patient p ON a.patient_id = p.id
                        WHERE a.doctor_id=? AND a.status='Completed'
                        ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$history = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Appointment History</title>
    <style>
        body { font-family: 'Segoe UI', Arial; background:#f4f6f9; margin:0; }
        .navbar { background:#3498db; padding:15px; text-align:center; }
        .navbar a { color:white; text-decoration:none; margin:0 15px; font-weight:bold; }
        .navbar a:hover { text-decoration:underline; }
        .section { background:#fff; padding:20px; margin:20px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; background:#fff; margin-top:10px; }
        th, td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background:#3498db; color:white; }
        tr:nth-child(even) { background:#f9f9f9; }
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

    <!-- Appointment History -->
    <div class="section">
        <h2>Completed Appointments</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            <?php while($row = $history->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['patient_email']) ?></td>
                <td><?= $row['appointment_date'] ?></td>
                <td><?= $row['appointment_time'] ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $row['status'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>