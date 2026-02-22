<?php
session_start();
include('db_connect.php');

if(!isset($_SESSION['doctor_id'])){
    header("Location: doctorlogin.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Update status + notify patient
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update appointment status
    $stmt = $conn->prepare("UPDATE appointment SET status=? WHERE id=? AND doctor_id=?");
    $stmt->bind_param("sii", $status, $id, $doctor_id);
    $stmt->execute();
    $stmt->close();

    // Create notification for patient
    $msg = "Your appointment has been " . strtolower($status);
    $stmt = $conn->prepare("INSERT INTO notifications (appointment_id, user_type, message) VALUES (?, 'patient', ?)");
    $stmt->bind_param("is", $id, $msg);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:blue;'>Status updated!</p>";
}

// Reschedule + notify patient
if(isset($_POST['reschedule'])){
    $id = $_POST['id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    // Update appointment date/time
    $stmt = $conn->prepare("UPDATE appointment SET appointment_date=?, appointment_time=? WHERE id=? AND doctor_id=?");
    $stmt->bind_param("ssii", $date, $time, $id, $doctor_id);
    $stmt->execute();
    $stmt->close();

    // Create notification for patient
    $msg = "Your appointment has been rescheduled to $date at $time";
    $stmt = $conn->prepare("INSERT INTO notifications (appointment_id, user_type, message) VALUES (?, 'patient', ?)");
    $stmt->bind_param("is", $id, $msg);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:orange;'>Appointment rescheduled!</p>";
}

// Delete
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM appointment WHERE id=? AND doctor_id=?");
    $stmt->bind_param("ii", $id, $doctor_id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:red;'>Appointment deleted!</p>";
}

// Fetch appointments
$stmt = $conn->prepare("SELECT a.id, p.name AS patient_name, p.email AS patient_email,
                               a.appointment_date, a.appointment_time, a.description, a.status
                        FROM appointment a
                        JOIN patient p ON a.patient_id = p.id
                        WHERE a.doctor_id=? ORDER BY a.appointment_date ASC");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$appointments = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointments</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f6f9; margin:0; padding:20px; }
        h2 { color:#2c3e50; }
        table { width:100%; border-collapse:collapse; background:#fff; margin-top:20px; }
        th, td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background:#3498db; color:white; }
        tr:nth-child(even) { background:#f9f9f9; }
        button { margin:2px; padding:5px 10px; }
        select, input { padding:5px; }
        .hidden { display:none; }
    </style>
    <script>
        function toggleReschedule(id) {
            var row = document.getElementById("reschedule-"+id);
            if(row.classList.contains("hidden")){
                row.classList.remove("hidden");
            } else {
                row.classList.add("hidden");
            }
        }
    </script>
</head>
<body>
    <h2>Appointments</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $appointments->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= $row['id'] ?><input type="hidden" name="id" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['patient_email']) ?></td>
                <td><?= $row['appointment_date'] ?></td>
                <td><?= date("H:i", strtotime($row['appointment_time'])) ?></td> <!-- 24-hour format -->
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <select name="status">
                        <option value="Pending" <?= $row['status']=="Pending"?"selected":"" ?>>Pending</option>
                        <option value="Approved" <?= $row['status']=="Approved"?"selected":"" ?>>Approved</option>
                        <option value="Completed" <?= $row['status']=="Completed"?"selected":"" ?>>Completed</option>
                    </select>
                </td>
                <td>
                    <button type="submit" name="update">Update Status</button>
                    <button type="button" onclick="toggleReschedule(<?= $row['id'] ?>)">Reschedule</button>
                    <button type="submit" name="delete">Delete</button>
                </td>
            </form>
        </tr>
        <!-- Hidden reschedule row -->
        <tr id="reschedule-<?= $row['id'] ?>" class="hidden">
            <form method="post">
                <td colspan="8">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <label>New Date:</label>
                    <input type="date" name="appointment_date" required>
                    <label>New Time (24h):</label>
                    <select name="appointment_time" required>
                        <option value="13:00">13:00</option>
                        <option value="13:30">13:30</option>
                        <option value="14:00">14:00</option>
                        <option value="14:30">14:30</option>
                        <option value="15:00">15:00</option>
                        <option value="15:30">15:30</option>
                        <option value="16:00">16:00</option>
                        <option value="16:30">16:30</option>
                    </select>
                    <button type="submit" name="reschedule">Confirm Reschedule</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>