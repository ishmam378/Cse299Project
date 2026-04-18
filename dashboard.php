<?php
session_start();
include("config/db.php");

// protect page
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// get student basic info
$query = "SELECT * FROM students WHERE student_id = $student_id";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// get latest study + attendance
$history_query = "SELECT * FROM student_history 
WHERE student_id = $student_id 
ORDER BY history_id DESC LIMIT 1";

$history_result = mysqli_query($conn,$history_query);
$history = mysqli_fetch_assoc($history_result);
?>

<?php include("templates/header.php"); ?>


<?php if(isset($_GET['updated'])){ ?>
    <p style="color:green;">Profile updated successfully!</p>
<?php } ?>

<?php if(isset($_GET['semester'])){ ?>
    <p style="color:green;">Semester plan saved successfully!</p>
<?php } ?>

<div class="main">

<h1>Dashboard</h1>

<h3>Welcome, <?php echo $student['name']; ?></h3>

<hr>

<h2>Student Info</h2>

<p><b>Student ID:</b> <?php echo $student['student_id']; ?></p>
<p><b>Email:</b> <?php echo $student['email']; ?></p>
<p><b>Department:</b> <?php echo $student['department']; ?></p>

<hr>

<h2>Latest Academic Status</h2>

<p><b>Study Hours:</b> 
<?php echo $history ? $history['study_hours'] : 'Not set'; ?>
</p>

<p><b>Attendance:</b> 
<?php echo $history ? $history['avg_attendance'] : 'Not set'; ?>%
</p>

<hr>

<h2>Actions</h2>

<div class="cards">

<div class="card">
<a href="update_profile.php" style="text-decoration:none; color:black;">
<h3>Update Profile</h3>
<p>Update study hours & attendance</p>
</a>
</div>

<div class="card">
<a href="semester.php" style="text-decoration:none; color:black;">
<h3>New Semester</h3>
<p>Add semester workload</p>
</a>
</div>

<div class="card">
<a href="prediction.php" style="text-decoration:none; color:black;">
<h3>View Prediction</h3>
<p>Check GPA & recommendations</p>
</a>
</div>

<div class="card">
<a href="logout.php" style="text-decoration:none; color:black;">
<h3>Logout</h3>
<p>Exit your account</p>
</a>
</div>

</div>

</div>

</div>

</body>
</html>