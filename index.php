<?php
session_start();

if(isset($_SESSION['student_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<h1>Campus Simulator</h1>

<a href="login.php">Login</a><br><br>
<a href="signup.php">Sign Up</a>
