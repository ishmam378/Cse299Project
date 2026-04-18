<?php
session_start();
include("config/db.php");

// protect page
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if(isset($_POST['predict'])){

    // get student (optional for future use)
    $student_query = "SELECT * FROM students WHERE student_id = $student_id";
    $student_result = mysqli_query($conn,$student_query);
    $student = mysqli_fetch_assoc($student_result);

    // get latest profile (study + attendance)
    $history_query = "SELECT * FROM student_history 
    WHERE student_id = $student_id 
    ORDER BY history_id DESC LIMIT 1";

    $history_result = mysqli_query($conn,$history_query);
    $history = mysqli_fetch_assoc($history_result);

    if($history){
        $study_hours = $history['study_hours'];
        $attendance = $history['avg_attendance'];
    } else {
        $error = "Please update your profile first.";
    }

    // get latest semester data
    $semester_query = "SELECT * FROM semester_plan 
    WHERE student_id = $student_id 
    ORDER BY plan_id DESC LIMIT 1";

    $semester_result = mysqli_query($conn,$semester_query);
    $semester = mysqli_fetch_assoc($semester_result);

    if(!$semester){
        $error = "Please add a semester plan first.";
    }

    if(!isset($error)){

        $credits = $semester['total_credits'];
        $job_hours = $semester['job_hours'];

        // base GPA (can improve later)
        $gpa = 3.0;

        // 🔥 ADVANCED MODEL
        $study_effect = sqrt($study_hours) * 0.3;

        $predicted_gpa = 
        ($gpa * 0.5) +
        $study_effect +
        ($attendance * 0.02) -
        ($credits * 0.04) -
        ($job_hours * 0.05);

        // personalization
        if($attendance > 85){
            $predicted_gpa += 0.2;
        }

        if($job_hours > 20){
            $predicted_gpa -= 0.3;
        }

        // clamp GPA
        $predicted_gpa = max(0, min(4, $predicted_gpa));

        // stress model
        $stress = 
        ($credits * 1.5) +
        ($job_hours * 3) -
        ($study_hours * 1.2);

        if($stress < 30){
            $stress_level = "Low";
        } elseif($stress < 60){
            $stress_level = "Moderate";
        } else {
            $stress_level = "High";
        }

        // risk
        if($predicted_gpa < 2.5){
            $risk = "High";
        } elseif($predicted_gpa < 3.2){
            $risk = "Medium";
        } else {
            $risk = "Low";
        }


        $risk_advice = [];

        if($risk == "High"){
            $risk_advice[] = "Immediate action required: reduce workload and focus on core subjects.";
            $risk_advice[] = "High probability of academic decline if current habits continue.";
            $risk_advice[] = "Seek support: adjust study plan or reduce job hours.";
        }

        elseif($risk == "Medium"){
            $risk_advice[] = "You are close to stable performance, but consistency is needed.";
            $risk_advice[] = "Improve study habits and avoid last-minute preparation.";
            $risk_advice[] = "Small changes in attendance and workload can significantly improve GPA.";
        }

        else{
            $risk_advice[] = "You are performing well academically.";
            $risk_advice[] = "Maintain your current study balance and discipline.";
            $risk_advice[] = "You can aim for further improvement by optimizing study efficiency.";
        }

                // =========================
        // ADVANCED RECOMMENDATIONS
        // =========================

        $academic = [];
        $lifestyle = [];
        $risk_advice = [];

        // 🔴 CRITICAL COMBINED LOGIC
        if($predicted_gpa < 2.5 && $stress_level == "High"){
            $risk_advice[] = "Critical: Reduce workload immediately and focus on recovery.";
        }

        // 📘 ACADEMIC IMPROVEMENT
        if($study_hours < 3){
            $needed = 5 - $study_hours;
            $academic[] = "Increase study time by at least $needed hours per day.";
        }

        if($credits > 18){
            $academic[] = "Your course load is heavy. Consider reducing credits next semester.";
        }

        if($predicted_gpa < 3.0){
            $academic[] = "Focus on difficult subjects and improve consistency in study habits.";
        }

        // 🧠 LIFESTYLE BALANCE
        if($job_hours > 20){
            $reduce = $job_hours - 15;
            $lifestyle[] = "Reduce work hours by at least $reduce hours/week to improve balance.";
        }

        if($stress_level == "High"){
            $lifestyle[] = "Take breaks, manage time better, and avoid burnout.";
        }

        if($stress_level == "Moderate"){
            $lifestyle[] = "Maintain a healthy balance between study and rest.";
        }

        // 📊 ATTENDANCE
        if($attendance < 75){
            $academic[] = "Increase attendance to at least 80% for better academic performance.";
        }

        // 🎯 SUMMARY
        if($risk == "High"){
            $summary = "High risk detected. Immediate action is required to prevent academic decline.";
        }
        else if($risk == "Medium"){
            $summary = "Moderate risk. Improvements in consistency and balance can boost performance.";
        }
        else{
            $summary = "Low risk. You are on track—maintain your current strategy.";
        }

        if(!isset($summary)) $summary = "";

            
            // SAVE ADVANCED RESULT
           

            $academic_text = !empty($academic) ? implode(" | ", $academic) : "No academic issues detected";
            $lifestyle_text = !empty($lifestyle) ? implode(" | ", $lifestyle) : "Balanced lifestyle";
            $risk_text = !empty($risk_advice) ? implode(" | ", $risk_advice) : "No critical risks";

            $insert = "INSERT INTO prediction_result 
            (student_id, predicted_gpa, stress_score, risk_level, academic_rec, lifestyle_rec, risk_advice, summary)
            VALUES
            (
            '$student_id',
            '$predicted_gpa',
            '$stress',
            '$risk',
            '$academic_text',
            '$lifestyle_text',
            '$risk_text',
            '$summary'
            )";

            mysqli_query($conn, $insert);
    }
}
?>

<?php include("templates/header.php"); ?>


<div class="main">

<h1>Prediction Engine</h1>

<form method="POST">
<button type="submit" name="predict">Run Prediction</button>
</form>

<?php if(isset($error)){ ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

<?php if(isset($predicted_gpa)){ ?>

<h2>Results</h2>

<p><b>Predicted GPA:</b> <?php echo round($predicted_gpa,2); ?></p>
<p><b>Stress Level:</b> <?php echo $stress_level; ?></p>

<?php
$color = "green";
if($risk == "High") $color = "red";
elseif($risk == "Medium") $color = "orange";
?>

<p style="color:<?php echo $color; ?>; font-weight:bold;">
Risk Level: <?php echo $risk; ?>
</p>

<?php if(!empty($academic)){ ?>
<h3>Academic Recommendations</h3>
<ul>
<?php foreach($academic as $rec){ ?>
<li><?php echo $rec; ?></li>
<?php } ?>
</ul>
<?php } ?>

<?php if(!empty($lifestyle)){ ?>
<h3>Lifestyle Recommendations</h3>
<ul>
<?php foreach($lifestyle as $rec){ ?>
<li><?php echo $rec; ?></li>
<?php } ?>
</ul>
<?php } ?>

<?php if(!empty($risk_advice)){ ?>
<h3>Risk Advice</h3>
<ul>
<?php foreach($risk_advice as $rec){ ?>
<li><?php echo $rec; ?></li>
<?php } ?>
</ul>
<?php } ?>

<?php } ?>

</div>

</div>

</body>
</html>