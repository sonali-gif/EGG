<?php
session_start();
$conn = new mysqli("localhost","root","","resume_db");

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$res = $conn->query("SELECT * FROM resumes WHERE id='$id' AND user_id='$user_id'");
$data = $res->fetch_assoc();

if(!$data){
    die("Not allowed!");
}
?>

<form method="POST" action="update.php">
<input type="hidden" name="id" value="<?= $id ?>">
<input name="skills" value="<?= $data['skills'] ?>">
<textarea name="experience"><?= $data['experience'] ?></textarea>
<button>Update</button>
</form>
