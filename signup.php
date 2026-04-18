<?php
include("config/db.php");

if(isset($_POST['signup'])){

$name = $_POST['name'];
$student_id = $_POST['student_id'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$department = $_POST['department'];
$cgpa = $_POST['cgpa'];


// validation
if(!preg_match('/^[0-9]{7}$/', $student_id)){
    echo "Student ID must be 7 digits";
    exit();
}

// insert
$query = "INSERT INTO students 
(student_id, name, email, department, current_cgpa, password)
VALUES
('$student_id','$name','$email','$department','$cgpa','$password')";

mysqli_query($conn,$query);

header("Location: login.php?success=1");
exit();
}
?>

<h2>Sign Up</h2>

<form method="POST">

<input name="name" placeholder="Name" required><br><br>

<input name="student_id" placeholder="7-digit Student ID" required><br><br>

<input name="email" placeholder="Email" required><br><br>

<input name="department" placeholder="Department" required><br><br>

<input name="cgpa" placeholder="Current CGPA" required><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<button type="submit" name="signup">Sign Up</button>

</form>