<?php
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "nemu_id";   


$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Request Time Out " . mysqli_connect_error());
} else {
    
}
?>