<?php
session_start();
$conn = new mysqli("localhost","root","","resume_db");

$user_id = $_SESSION["user_id"];

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"];
$email = $data["email"];
$phone = $data["phone"];
$skills = $data["skills"];
$experience = $data["experience"];

$sql = "INSERT INTO resumes(name,email,phone,skills,experience,user_id)
VALUES('$name','$email','$phone','$skills','$experience','$user_id')";

if($conn->query($sql)){
    echo json_encode(["status"=>"success"]);
}else{
    echo json_encode(["status"=>"error"]);
}
?>
