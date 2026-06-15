<?php
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "nemu_id";   
//gatau kenapa nama database nya nemu_id, tapi yaudah lah mau gmn lagi

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Request Time Out " . mysqli_connect_error());
} else {
    
}
?>