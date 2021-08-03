<?php
session_start();
// $do = "";
if(isset($_GET['do'])){
    $do = $_GET['do'];
}else{
    $do = "manage" ;
}
?>

<?php if(isset($_SESSION['USER_NAME'])):?>
<?php include "resources/includes/header.inc"?>
<?php require "config.php"?>
<?php include "resources/includes/navbar.inc"?>


<?php if($do == "manage"):?>

<?php
        $stmt= $con->prepare("SELECT * FROM users WHERE groupid=1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
<div class="container">
    <h1 class="text-center">All Members</h1>
    <a href="?do=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add members</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">UserName</th>
                <th scope="col">Email</th>
                <th scope="col">Date</th>
                <th scope="col">Control</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row):?>
            <tr>
                <th scope="row"><?php echo $row["username"]?></th>
                <td><?php echo $row["email"]?></td>
                <td><?php echo $row["created_at"]?></td>
                <td>
                    <a class="btn btn-info" href="?do=show&userid=<?php echo $row["user_id"]?>"><i
                            class="fas fa-eye"></i></a>
                    <a class="btn btn-warning" href="?do=edit&userid=<?php echo $row["user_id"]?>"><i
                            class="fas fa-edit"></i></a>
                    <a class="btn btn-danger" href="?do=delete"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php elseif($do == "add"):?>
<div class="container">
    <h1 class="Add members"></h1>
    <form method="post" action="?do=insert">
        <div class="mb-3">
            <label class="form-label">User Name</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="fullname">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($do == "insert"):?>
<?php
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $username = $_POST['username'];
            $email    = $_POST['email'];
            $password = sha1($_POST['password']);
            $fullname = $_POST['fullname'];

            $stmt = $con->prepare("INSERT INTO users (username,password,email,fullname,groupid,created_at) VALUES(?,?,?,?,0,now())");
            $stmt->execute(array($username,$password,$email,$fullname));
            header("location:members.php?do=add");
        }
    ?>
<?php elseif($do == "edit"):?>
<?php
    $userid = isset($_GET['userid'])&&is_numeric($_GET['userid'])?intval($_GET['userid']):0;
    $stmt = $con->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    ?>
<?php if($count == 1):?>
<div class="container">
    <h1 class="text-center">Edit Member</h1>
    <form method="post" action="?do=update">
        <div class="mb-3">
            <input type="hidden" class="form-control" value="<?= $row['user_id']?>" name="userid">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="text" class="form-control" value="<?= $row['username']?>" name="username">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Username</label>
            <input type="email" class="form-control" id="exampleInputEmail1" value="<?= $row['email']?>" name="email">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="newpassword">
            <input type="hidden" class="form-control" value="<?= $row['password']?>" name="oldpassword">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">fullname</label>
            <input type="text" class="form-control" value="<?= $row['fullname']?>" name="fullname">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<?php endif?>
<?php elseif($do == "update"):?>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $userid     = $_POST['userid'];
        $username   = $_POST['username'];
        $email      = $_POST['email'];
        $fullname   = $_POST['fullname'];
        $password   = empty($_POST['newpassword'])?$_POST['oldpassword']:$_POST['newpassword'];
        $hashedpass = sha1($password);
        $stmt = $con->prepare("UPDATE users SET username=? , password=? , email=? , fullname=? WHERE user_id=?");
        $stmt->execute(array($username , $hashedpass , $email , $fullname , $userid));
        header("location:members.php");
    }
    ?>
<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>
<?php 
        $userid = $_GET["userid"] ;
        $stmt= $con->prepare("SELECT * FROM users WHERE user_id=?");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();


        echo "<pre>";
        print_r($row);
        echo "</pre>";
    ?>
<a href="members.php" class="btn btn-dark">Back</a>
<?php endif?>

<?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php")?>
<?php endif?>