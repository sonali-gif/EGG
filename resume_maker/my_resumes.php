<?php
session_start();
$conn = new mysqli("localhost","root","","resume_db");

$user_id = $_SESSION["user_id"];

$result = $conn->query("SELECT * FROM resumes WHERE user_id='$user_id'");

echo "<h2>My Resumes</h2>";

while($row = $result->fetch_assoc()){
    echo "
    <div style='border:1px solid #ccc;padding:15px;margin:10px'>
        <b>{$row['name']}</b><br>
        {$row['email']}<br>
        <a href='edit.php?id={$row['id']}'>Edit</a>
    </div>
    ";
}
?>
