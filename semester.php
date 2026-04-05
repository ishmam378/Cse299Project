<?php

include("config/db.php");

if(isset($_POST['submit'])){

$semester = $_POST['semester'];
$courses = $_POST['courses'];
$credits = $_POST['credits'];
$job_hours = $_POST['job_hours'];

$query = "INSERT INTO semester_plan 
(semester, courses, total_credits, job_hours)
VALUES
('$semester', '$courses', '$credits', '$job_hours')";

mysqli_query($conn,$query);

echo "Semester plan saved successfully!";

}

?>


<?php include("templates/header.php"); ?>
<?php include("templates/sidebar.php"); ?>

<div class="main">

<h1>Semester Planner</h1>

<form method="POST" class="student-form">

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