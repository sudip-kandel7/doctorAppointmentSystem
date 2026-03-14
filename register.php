<?php



session_start();
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($role == "patient") {

        $stmt = $mysqli->prepare("INSERT INTO patient(name,email,password) VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['patient_id'] = $stmt->insert_id;
            header("Location: patient/patientdashboard.php");
            exit();
        } else {
            $error = "Registration failed.";
        }

        $stmt->close();
    }

    if ($role == "doctor") {

        $specialization = $_POST['specialization'];

        if ($specialization == "Other") {
            $specialization = $_POST['other_specialization'];
        }

        $experience = $_POST['experience'];
        $available_from = $_POST['available_from'];
        $available_to = $_POST['available_to'];

        $days = "";
        if (isset($_POST['available_days'])) {
            $days = implode(",", $_POST['available_days']);
        }

        // Handle profile picture upload
        $targetFile = "";
        if (!empty($_FILES["profile_pic"]["name"])) {

            $targetDir = "uploads/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile);
        }

        $stmt = $mysqli->prepare("INSERT INTO doctor
        (name,email,password,specialization,profile_pic,experience,available_from,available_to,available_days)
        VALUES(?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "sssssisss",
            $name,
            $email,
            $hashedPassword,
            $specialization,
            $targetFile,
            $experience,
            $available_from,
            $available_to,
            $days
        );

        if ($stmt->execute()) {
            $_SESSION['doctor_id'] = $stmt->insert_id;
            header("Location: doctor/doctordashboard.php");
            exit();
        } else {
            $error = "Registration failed.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <style>
    body {
        font-family: Arial;
        background: #f4f6f9;
    }

    .form-box {
        max-width: 450px;
        margin: 60px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
    }

    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input,
    select {
        width: 100%;
        padding: 8px 10px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    button[type="submit"] {
        background: #27ae60;
        color: white;
        padding: 10px;
        border: none;
        width: 100%;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
    }

    button[type="submit"]:hover {
        background: #1e8449;
    }

    .error {
        color: red;
        text-align: center;
        margin-bottom: 10px;
    }

    .links {
        text-align: center;
        margin-top: 15px;
    }

    a {
        color: #3498db;
        font-weight: bold;
        text-decoration: none;
    }

    .password-box {
        position: relative;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .password-box input {
        margin: 0;
        padding-right: 36px;
        width: 100%;
        box-sizing: border-box;
    }

    .password-box .eye-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 15px;
        line-height: 1;
        user-select: none;
        color: #666;
    }

    .days {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
        margin-bottom: 10px;
    }

    .days input[type="checkbox"] {
        display: none;
    }

    .days label {
        font-weight: normal;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        margin: 0;
        background: #f9f9f9;
        color: #444;
        transition: background 0.2s, color 0.2s, border-color 0.2s;
    }

    .days input[type="checkbox"]:checked+label {
        background: #27ae60;
        color: white;
        border-color: #27ae60;
    }

    .days label:hover {
        border-color: #27ae60;
        color: #27ae60;
    }
    </style>

</head>

<body>

    <div class="form-box">

        <h2>Register</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Register As</label>
            <select name="role" id="role" onchange="toggleDoctor()" required>
                <option value="patient" selected>Patient</option>
                <option value="doctor">Doctor</option>
            </select>

            <label>Full Name</label>
            <input type="text" name="name" required>

            <label>Email Address</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <div class="password-box">
                <input type="password" name="password" id="password" required>
                <span class="eye-icon" onclick="togglePassword()">&#128065;</span>
            </div>

            <div id="doctorFields" style="display:none;">

                <label>Specialization</label>
                <select name="specialization" id="specialization" onchange="toggleOtherSpec()">
                    <option value="">-- Select Specialization --</option>
                    <option value="Gynecologist">Gynecologist</option>
                    <option value="Cardiologist">Cardiologist</option>
                    <option value="Dermatologist">Dermatologist</option>
                    <option value="Pediatrician">Pediatrician</option>
                    <option value="Neurologist">Neurologist</option>
                    <option value="Orthopedic">Orthopedic</option>
                    <option value="General Physician">General Physician</option>
                    <option value="Other">Other</option>
                </select>

                <input type="text" name="other_specialization" id="otherSpec"
                    placeholder="Enter specialization if not listed" style="display:none;">

                <label>Years of Experience</label>
                <input type="number" name="experience" min="0">

                <label>Profile Picture</label>
                <input type="file" name="profile_pic" accept="image/*">

                <label>Available From</label>
                <input type="time" name="available_from">

                <label>Available To</label>
                <input type="time" name="available_to">

                <label>Available Days</label>

                <div class="days">
                    <input type="checkbox" name="available_days[]" value="Sunday" id="day_sun">
                    <label for="day_sun">Sun</label>

                    <input type="checkbox" name="available_days[]" value="Monday" id="day_mon">
                    <label for="day_mon">Mon</label>

                    <input type="checkbox" name="available_days[]" value="Tuesday" id="day_tue">
                    <label for="day_tue">Tue</label>

                    <input type="checkbox" name="available_days[]" value="Wednesday" id="day_wed">
                    <label for="day_wed">Wed</label>

                    <input type="checkbox" name="available_days[]" value="Thursday" id="day_thu">
                    <label for="day_thu">Thu</label>

                    <input type="checkbox" name="available_days[]" value="Friday" id="day_fri">
                    <label for="day_fri">Fri</label>

                    <input type="checkbox" name="available_days[]" value="Saturday" id="day_sat">
                    <label for="day_sat">Sat</label>
                </div>

            </div>

            <button type="submit">Register</button>

        </form>

        <div class="links">
            <p>Already registered? <a href="login.php">Login Here</a></p>
        </div>

    </div>

    <script>
    function toggleDoctor() {
        let role = document.getElementById("role").value;
        let doc = document.getElementById("doctorFields");
        doc.style.display = (role == "doctor") ? "block" : "none";
    }

    function toggleOtherSpec() {
        let spec = document.getElementById("specialization").value;
        let other = document.getElementById("otherSpec");
        if (spec === "Other") {
            other.style.display = "block";
            other.required = true;
        } else {
            other.style.display = "none";
            other.required = false;
        }
    }

    function togglePassword() {
        let pass = document.getElementById("password");
        pass.type = (pass.type === "password") ? "text" : "password";
    }
    </script>

</body>

</html>