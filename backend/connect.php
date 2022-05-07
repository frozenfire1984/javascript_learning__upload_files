<?php
error_reporting(0);
$user = "root";
$pass = "";
$host = "127.0.0.1";
$db = "crud";

if(!$conn=mysqli_connect($host, $user, $pass, $db)) {
    throw new Exception("connection bd failed!");
}
?>