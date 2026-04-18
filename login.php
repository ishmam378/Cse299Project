<?php
session_start();
include("config/db.php");

if(isset($_POST['login'])){

$student_id = $_POST['student_id'];
$password = $_POST['password'];

$query = "SELECT * FROM students WHERE student_id='$student_id'";
$result = mysqli_query($conn,$query);
$user = mysqli_fetch_assoc($result);

if($user && password_verify($password, $user['password'])){

    $_SESSION['student_id'] = $student_id;
    header("Location: dashboard.php");
    exit();

} else {
    $error = "Invalid login";
}
}
?>

<?php if(isset($_GET['success'])){ ?>
<p style="color:green;">Account created successfully!</p>
<?php } ?>

<form method="POST">
<input name="student_id" placeholder="Student ID" required><br><br>
<input type="password" name="password" placeholder="Password" required><br><br>
<button name="login">Login</button>
</form>

<div style="position:absolute; top:20px; right:20px;">
    <a href="signup.php">Sign Up</a>
</div>

<?php if(isset($error)){ echo "<p style='color:red;'>$error</p>"; } ?>