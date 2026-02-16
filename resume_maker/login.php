<?php
session_start();
$conn = new mysqli("localhost","root","","resume_db");

$username = $_POST["username"];
$password = $_POST["password"];

$result = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $result->fetch_assoc();

if($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    header("Location: index.html");
} else {
    echo "Wrong login!";
}
?>
