<?php
$conn = new mysqli("localhost","root","","gisweb69");

if($conn->connect_error){
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: ".$conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>