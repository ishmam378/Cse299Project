<?php
session_start();
include("config/db.php");

// protect page
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if(isset($_POST['update'])){

    $study_hours = $_POST['study_hours'];
    $attendance = $_POST['attendance'];

    $query = "INSERT INTO student_history 
    (student_id, study_hours, avg_attendance)
    VALUES
    ('$student_id','$study_hours','$attendance')";

    if(mysqli_query($conn,$query)){
    header("Location: dashboard.php?updated=1");
    exit();
    }
     else {
        $error = "Error saving data";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
</head>
<body>

<h2>Update Profile</h2>

<?php if(isset($success)){ ?>
    <p style="color:green;"><?php echo $success; ?></p>
<?php } ?>

<?php if(isset($error)){ ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

<label>Study Hours per Day</label><br>
<input type="number" name="study_hours" required><br><br>

<label>Attendance (%)</label><br>
<input type="number" name="attendance" required><br><br>

<button type="submit" name="update">Save</button>

</form>

</body>
</html>