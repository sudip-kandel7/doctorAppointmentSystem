<?php
session_start();
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == "doctor") {

        $stmt = $mysqli->prepare("SELECT id,password FROM doctor WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();

        if($doctor && password_verify($password,$doctor['password'])){
            $_SESSION['doctor_id'] = $doctor['id'];
            header("Location: doctor/doctordashboard.php");
            exit();
        } else {
            $error="Invalid doctor email or password.";
        }
        $stmt->close();
    }

    if ($role == "patient") {

        $stmt = $mysqli->prepare("SELECT id,password FROM patient WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();

        if($patient && password_verify($password,$patient['password'])){
            $_SESSION['patient_id'] = $patient['id'];
            header("Location: patient/patientdashboard.php");
            exit();
        } else {
            $error="Invalid patient email or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <style>
    body {
        font-family: Arial;
        background: #f4f6f9;
    }

    .form-box {
        max-width: 420px;
        margin: 80px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background: #3498db;
        color: white;
        padding: 10px;
        border: none;
        width: 100%;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }

    button:hover {
        background: #2980b9;
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
        color: #27ae60;
        font-weight: bold;
        text-decoration: none;
    }
    </style>

</head>

<body>

    <div class="form-box">

        <h2>Login</h2>

        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">

            <select name="role" required>
                <option value="">Login As</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>

            <input type="email" name="email" placeholder="Email Address" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>

        </form>

        <div class="links">
            <p>No account? <a href="register.php">Register Here</a></p>
        </div>

    </div>

</body>

</html>