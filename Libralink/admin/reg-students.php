<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // Block student
    if(isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }

    // Activate student
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-students.php');
    }

    // Delete student
    if(isset($_GET['delid'])) {
        $id = intval($_GET['delid']);
        $sql = "DELETE FROM tblstudents WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Student deleted successfully";
        header('location:reg-students.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Manage Registered Students | Library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>

<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Manage Registered Students</h4>
            </div>
        </div>

        <!-- Student Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Registered Students</div>
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
                                        <th>Status</th>
                                        <th>Toggle</th>
                                        <th>Actions</th>
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
<tr>
    <td><?php echo htmlentities($cnt); ?></td>
    <td><?php echo htmlentities($result->StudentId); ?></td>
    <td><?php echo htmlentities($result->FullName); ?></td>
    <td><?php echo htmlentities($result->EmailId); ?></td>
    <td><?php echo htmlentities($result->MobileNumber); ?></td>
    <td><?php echo htmlentities($result->RegDate); ?></td>
    <td>
        <?php echo $result->Status == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Blocked</span>'; ?>
    </td>
    <td>
        <?php if($result->Status == 1) { ?>
            <a href="reg-students.php?inid=<?php echo htmlentities($result->id); ?>"
               onclick="return confirm('Are you sure you want to block this student?');">
                <button class="btn btn-danger btn-sm">Block</button>
            </a>
        <?php } else { ?>
            <a href="reg-students.php?id=<?php echo htmlentities($result->id); ?>"
               onclick="return confirm('Are you sure you want to activate this student?');">
                <button class="btn btn-success btn-sm">Activate</button>
            </a>
        <?php } ?>
    </td>
    <td>
        <a href="edit-student.php?id=<?php echo htmlentities($result->id); ?>">
            <button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</button>
        </a>
        <a href="reg-students.php?delid=<?php echo htmlentities($result->id); ?>"
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
                </div> <!-- End panel -->
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<!-- Scripts -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script src="assets/js/custom.js"></script>

</body>
</html>
<?php } ?>
