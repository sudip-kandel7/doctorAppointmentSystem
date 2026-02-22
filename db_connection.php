<?php
$mysqli = new mysqli("localhost", "root", "", "doctorappointmentweb");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>