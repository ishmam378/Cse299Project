
<?php
session_start();

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}
?>

<?php

include("config/db.php");

if(isset($_POST['submit'])){

$student_id = $_SESSION['student_id'];
$semester = $_POST['semester'];
$courses = $_POST['courses'];
$credits = $_POST['credits'];
$job_hours = $_POST['job_hours'];

$query = "INSERT INTO semester_plan 
(student_id,semester, courses, total_credits, job_hours)
VALUES
('$student_id','$semester', '$courses', '$credits', '$job_hours')";

if(mysqli_query($conn,$query)){
    header("Location: dashboard.php?semester=1");
    exit();
} else {
    $error = "Error saving semester data";
}
}

?>


<?php include("templates/header.php"); ?>


<div class="main">

<h1>Semester Planner</h1>

<form method="POST" class="student-form">


<label>Student ID</label>
<input type="number" name="student_id" required>

<label>Semester</label>
<input type="text" name="semester" placeholder="e.g. Fall 2026" required>

<label>Number of Courses</label>
<input type="number" name="courses" required>

<label>Total Credits</label>
<input type="number" name="credits" required>

<label>Part-time Job Hours per Week</label>
<input type="number" name="job_hours" required>

<button type="submit" name="submit">Save Semester Plan</button>

</form>

</div>

</div>

</body>
</html>
