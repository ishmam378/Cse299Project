
<?php


include("config/db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $gpa = $_POST['gpa'];
    $current_cgpa = $_POST['current_cgpa'];
    $study_hours = $_POST['study_hours'];
    $attendance = $_POST['attendance'];
    $job_hours = $_POST['job_hours'];

    $stmt = $conn->prepare("INSERT INTO students 
    (name,email,department,previous_gpa,current_cgpa,study_hours,attendance,job_hours)
    VALUES (?,?,?,?,?,?,?,?)");

    $stmt->bind_param("ssssssss", $name, $email, $department, $gpa, $current_cgpa, $study_hours, $attendance, $job_hours);

    session_start();  // start session

if($stmt->execute()){
    
    // store the inserted student's ID in session
    $_SESSION['student_id'] = $conn->insert_id;

    echo "Data inserted successfully!";

} else {
    echo "Error: " . $stmt->error;
}

}
?>


<?php include("templates/header.php"); ?>


<div class="main">

<h1>Student Profile</h1>

<form method="POST" class="student-form">

<label>Name</label>
<input type="text" name="name" placeholder="Enter your name">

<label>Email</label>
<input type="email" name="email" placeholder="Enter your email">

<label>Department</label>
<input type="text" name="department" placeholder="Enter your department">

<label>Previous GPA</label>
<input type="number" step="0.01" min="0" max="4" name="gpa" required>

<label>Current CGPA</label>
<input type="number" step="0.01" min="0" max="4" name="current_cgpa" required>

<label>Study Hours per Week</label>
<input type="number" name="study_hours">

<label>Average Attendance (%)</label>
<input type="number" name="attendance">

<label>Part-time Job Hours per Week</label>
<input type="number" name="job_hours">

<button type="submit" name="submit">Save Profile</button>

</form>

</div>

</div>

</body>
</html>
