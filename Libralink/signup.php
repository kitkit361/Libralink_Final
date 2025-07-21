<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['signup'])) {
    $studentid = $_POST['studentid']; // added
    $fullname = $_POST['fullname'];
    $email = $_POST['emailid'];
    $mobileno = $_POST['mobileno'];
    $password = md5($_POST['password']);

    // Optional: check for duplicate StudentId
    $checksql = "SELECT StudentId FROM tblstudents WHERE StudentId = :studentid";
    $checkquery = $dbh->prepare($checksql);
    $checkquery->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $checkquery->execute();

    if($checkquery->rowCount() > 0) {
        $_SESSION['errmsg'] = "Student ID already exists!";
    } else {
        $sql = "INSERT INTO tblstudents(StudentId, FullName, EmailId, MobileNumber, Password) 
                VALUES(:studentid, :fullname, :email, :mobileno, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        $_SESSION['msg'] = "Registration successful. You can now login.";
        header("location:index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Online Library Management System | Signup</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
       <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Student Signup</h4>
                </div>
            </div> 

            <?php if($_SESSION['errmsg']!="") { ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="alert alert-danger">
                        <strong>Error :</strong> 
                        <?php echo htmlentities($_SESSION['errmsg']); ?>
                        <?php echo htmlentities($_SESSION['errmsg']=""); ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Student ID</label>
                            <input class="form-control" type="text" name="studentid" autocomplete="off" required />
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input class="form-control" type="text" name="fullname" autocomplete="off" required />
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control" type="email" name="emailid" autocomplete="off" required />
                        </div>
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input class="form-control" type="text" name="mobileno" autocomplete="off" required />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password" autocomplete="off" required />
                        </div>
                        <button type="submit" name="signup" class="btn btn-info">Register Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
