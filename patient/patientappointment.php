<?php
session_start();
include('../db_connection.php'); // must define $mysqli

// Ensure patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patientlogin.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$doctor_id  = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$message    = "";

// Fetch patient name for notifications
$patientName = "";
$stmtName = $mysqli->prepare("SELECT name FROM patient WHERE id=?");
$stmtName->bind_param("i", $patient_id);
$stmtName->execute();
$stmtName->bind_result($patientName);
$stmtName->fetch();
$stmtName->close();

// Fetch doctor details if doctor_id is provided
$doctorName = "";
$doctorSpec = "";
$doctorStart = "";
$doctorEnd   = "";
$doctorDays  = "";

if (!empty($doctor_id)) {
    $stmt = $mysqli->prepare("SELECT name, specialization, available_from, available_to, available_days 
                              FROM doctor WHERE id=?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->bind_result($doctorName, $doctorSpec, $doctorStart, $doctorEnd, $doctorDays);
    $stmt->fetch();
    $stmt->close();
}

/* -------------------- CREATE Appointment -------------------- */
if (isset($_POST['create'])) {
    $doctor_id = $_POST['doctor_id'];
    $date      = $_POST['appointment_date'];
    $time      = $_POST['appointment_time'];
    $desc      = $_POST['description'];

    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM appointment WHERE doctor_id=? AND appointment_date=? AND appointment_time=?");
    $stmt->bind_param("iss", $doctor_id, $date, $time);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if ($cnt > 0) {
        $message = "❌ Doctor is already booked at this time. Please select another time.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO appointment 
            (patient_id, doctor_id, appointment_date, appointment_time, description, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $desc);

        if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;
            $message = "✅ Appointment booked successfully!";

            // Notify doctor with patient name
            $msg = "$patientName booked a new appointment on $date at $time";
            $stmt2 = $mysqli->prepare("INSERT INTO notifications (appointment_id, user_type, message, seen) VALUES (?, 'doctor', ?, 0)");
            $stmt2->bind_param("is", $appointment_id, $msg);
            $stmt2->execute();
            $stmt2->close();
        } else {
            $message = "❌ Error booking appointment.";
        }
        $stmt->close();
    }
}

/* -------------------- UPDATE Appointment -------------------- */
if (isset($_POST['update'])) {
    $id   = $_POST['id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM appointment 
        WHERE doctor_id=(SELECT doctor_id FROM appointment WHERE id=?) 
        AND appointment_date=? AND appointment_time=? AND id<>?");
    $stmt->bind_param("issi", $id, $date, $time, $id);
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if ($cnt > 0) {
        $message = "❌ Doctor is already booked at this time. Please select another time.";
    } else {
        $stmt = $mysqli->prepare("UPDATE appointment 
            SET appointment_date=?, appointment_time=?, status='Rescheduled' 
            WHERE id=? AND patient_id=?");
        $stmt->bind_param("ssii", $date, $time, $id, $patient_id);

        if ($stmt->execute()) {
            $message = "✅ Appointment rescheduled successfully!";

            // Notify doctor with patient name
            $msg = "$patientName rescheduled appointment #$id to $date at $time";
            $stmt2 = $mysqli->prepare("INSERT INTO notifications (appointment_id, user_type, message, seen) VALUES (?, 'doctor', ?, 0)");
            $stmt2->bind_param("is", $id, $msg);
            $stmt2->execute();
            $stmt2->close();
        } else {
            $message = "❌ Error updating appointment.";
        }
        $stmt->close();
    }
}

/* -------------------- DELETE Appointment -------------------- */
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $mysqli->prepare("DELETE FROM appointment WHERE id=? AND patient_id=?");
    $stmt->bind_param("ii", $id, $patient_id);

    if ($stmt->execute()) {
        $message = "✅ Appointment cancelled successfully!";

        // Notify doctor with patient name
        $msg = "$patientName cancelled appointment #$id";
        $stmt2 = $mysqli->prepare("INSERT INTO notifications (appointment_id, user_type, message, seen) VALUES (?, 'doctor', ?, 0)");
        $stmt2->bind_param("is", $id, $msg);
        $stmt2->execute();
        $stmt2->close();
    } else {
        $message = "❌ Error cancelling appointment.";
    }
    $stmt->close();
}

/* -------------------- READ Appointments -------------------- */
$stmt = $mysqli->prepare("SELECT a.id, d.name AS doctor_name, d.specialization, 
        d.available_from, d.available_to,
        a.appointment_date, a.appointment_time, a.description, a.status 
    FROM appointment a 
    JOIN doctor d ON a.doctor_id = d.id 
    WHERE a.patient_id=? 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient Appointments</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background:#f4f6f9;
            margin:20px;
        }
        h2 { color:#2c3e50; }
        .message { margin:10px 0; padding:10px; border-radius:6px; font-weight:bold; }
        .success { background:#d4edda; color:#155724; }
        .error { background:#f8d7da; color:#721c24; }
        form { margin-bottom:20px; background:#fff; padding:15px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        input, textarea, select { margin:5px 0; padding:8px; width:100%; border:1px solid #ccc; border-radius:6px; }
        button { background:#27ae60; color:white; border:none; padding:10px; cursor:pointer; border-radius:6px; font-weight:bold; }
        button:hover { background:#1e8449; }
        table { width:100%; border-collapse:collapse; margin-top:20px; background:white; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
        th, td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background:#3498db; color:white; }
        tr:nth-child(even) { background:#f9f9f9; }
    </style>
</head>
<body>
    <h2>Book Appointment</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Booking Form -->
    <form method="POST">
        <?php if (!empty($doctor_id)): ?>
            <label>Doctor:</label>
            <input type="text" value="<?php echo $doctorName . ' (' . $doctorSpec . ')'; ?>" readonly>
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
        <?php else: ?>
            <label>Doctor ID:</label>
            <input type="text" name="doctor_id" required>
        <?php endif; ?>

        <label>Date:</label>
        <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">

        <label>Time:</label>
        <select name="appointment_time" required>
            <?php
            if (!empty($doctorStart) && !empty($doctorEnd)) {
                $start = strtotime($doctorStart);
                $end   = strtotime($doctorEnd);
                for ($t = $start; $t < $end; $t += 1800) {
                    $slot = date("H:i", $t);
                    echo "<option value='$slot'>$slot</option>";
                }
            }
            ?>
        </select>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <button type="submit" name="create">Book Appointment</button>
    </form>

    <!-- Appointments Table -->
    <h2>Your Appointments</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Doctor</th>
            <th>Specialization</th>
            <th>Date</th>
            <th>Time</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['doctor_name'] ?></td>
            <td><?= $row['specialization'] ?></td>
            <td><?= $row['appointment_date'] ?></td>
            <td><?= $row['appointment_time'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <!-- Reschedule Form -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">

                    <select name="appointment_time" required>
                        <?php
                        $start = strtotime($row['available_from']);
                        $end   = strtotime($row['available_to']);
                        for ($t = $start; $t < $end; $t += 1800) {
                            $slot = date("H:i", $t);
                            // Pre-select current appointment time
                            $selected = ($slot == $row['appointment_time']) ? "selected" : "";
                            echo "<option value='$slot' $selected>$slot</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" name="update">Reschedule</button>
                </form>

                <!-- Cancel Form -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete" onclick="return confirm('Cancel this appointment?');">Cancel</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>