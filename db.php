<?php
$host = "sql109.infinityfree.com"; 
$user = "if0_42403377";     
$pass = "Mathayel1";          
$dbname = "if0_42403377_myfrist";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>