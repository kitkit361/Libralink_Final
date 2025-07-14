<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit;
}

$studentId = intval($_GET['id']);

// Update student if form submitted
if(isset($_POST['update'])) {
    $studentid = $_POST['studentid'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    $sql = "UPDATE tblstudents SET StudentId=:studentid, FullName=:fullname, EmailId=:email, MobileNumber=:mobile WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':id', $studentId, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['msg'] = "Student updated successfully!";
    header("Location: reg-students.php");
    exit;
}

// Fetch student info
$sql = "SELECT * FROM tblstudents WHERE id=:id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $studentId, PDO::PARAM_INT);
$query->execute();
$student = $query->fetch(PDO::FETCH_OBJ);

if(!$student) {
    echo "Student not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Edit Student</h2>
    <form method="post">
        <div class="form-group">
            <label>Student ID</label>
            <input type="text" name="studentid" value="<?php echo htmlentities($student->StudentId); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo htmlentities($student->FullName); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlentities($student->EmailId); ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" value="<?php echo htmlentities($student->MobileNumber); ?>" class="form-control" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Student</button>
        <a href="reg-students.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
