<?php session_start()?>
<?php include "resources/includes/header.inc"?>
<?php require "config.php"?>
<!-- <?php include "resources/includes/navbar.inc"?> -->

<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $adminusername = $_POST['adminusername'];
    $adminpassword = $_POST['adminpassword'];
    $hashedhpass = sha1($adminpassword);

    $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=? AND groupid=1");
    $stmt->execute(array($adminusername , $hashedhpass ));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    $in_DB =1 ;

if($count == $in_DB){
    $_SESSION['USER_NAME'] = $adminusername;
    $_SESSION['USER_ID'] = $row['user_id'];
    $_SESSION['FULL_NAME'] = $row['fullname'];
    $_SESSION['GROUB_ID'] = $row['groupid'];
    header("location:dashboard.php");
    exit();
}else{
    echo "cheack username or password";
}
}

?>





<div class="log-in">
    <h1 class="text-center">Admin login</h1>
    <div class="container">
        <form method="post" action="<?php $_SERVER['PHP_SELF']?>">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="text" class="form-control" name="adminusername">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="adminpassword">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <div>


        <?php include "resources/includes/footer.inc"?>