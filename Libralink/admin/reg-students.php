<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 

// ✅ DELETE LOGIC
if(isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM tblstudents WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $_SESSION['delmsg'] = "Student deleted successfully";
    header("Location: reg-students.php");
    exit;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Online Library Management System | Registered Students</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Registered Students</h4>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="row">
            <?php if($_SESSION['error']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo htmlentities($_SESSION['error']); ?>
                        <?php $_SESSION['error']=""; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if($_SESSION['msg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success:</strong> <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php $_SESSION['msg']=""; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if($_SESSION['delmsg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Deleted:</strong> <?php echo htmlentities($_SESSION['delmsg']); ?>
                        <?php $_SESSION['delmsg']=""; ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Student Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Student List
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Reg Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = "SELECT * FROM tblstudents";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;
foreach($results as $result) {
?>
    <tr class="odd gradeX">
        <td><?php echo htmlentities($cnt); ?></td>
        <td><?php echo htmlentities($result->StudentId); ?></td>
        <td><?php echo htmlentities($result->FullName); ?></td>
        <td><?php echo htmlentities($result->EmailId); ?></td>
        <td><?php echo htmlentities($result->MobileNumber); ?></td>
        <td><?php echo htmlentities($result->RegDate); ?></td>
        <td>
            <a href="edit-student.php?id=<?php echo htmlentities($result->id); ?>">
                <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
            </a>
            <a href="reg-students.php?id=<?php echo htmlentities($result->id); ?>&del=delete"
               onclick="return confirm('Are you sure you want to delete this student?');">
                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
            </a>
        </td>
    </tr>
<?php $cnt++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Panel -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>

<?php } ?>
