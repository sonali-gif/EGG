<?php
session_start();
$conn = new mysqli("localhost","root","","resume_db");

$id = $_POST["id"];
$user_id = $_SESSION["user_id"];
$skills = $_POST["skills"];
$experience = $_POST["experience"];

$conn->query("UPDATE resumes 
SET skills='$skills', experience='$experience' 
WHERE id='$id' AND user_id='$user_id'");

header("Location: my_resumes.php");
?>
