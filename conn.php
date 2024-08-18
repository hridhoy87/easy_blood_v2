<?php
$conn = new mysqli('127.0.0.1','root','','easy_blood');

if(!$conn){
    die(mysqli_error($conn));
}
?>