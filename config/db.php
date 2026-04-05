<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "campus_simulator";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn){
    die("Database connection failed");
}

?>