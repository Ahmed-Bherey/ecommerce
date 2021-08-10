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
    // start pagination
    $recorded_per_page = 5;
    $page = isset($_GET['page'])?$_GET['page']:1;
    $start_from = ($page-1)* $recorded_per_page;
    // end pagination
        $stmt= $con->prepare("SELECT * FROM users WHERE groupid=1 LIMIT $start_from , $recorded_per_page");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
<div class="container">
    <h1 class="text-center">All Members</h1>
    <a href="?do=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add members</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Photo</th>
                <th scope="col">UserName</th>
                <th scope="col">Email</th>
                <th scope="col">Date</th>
                <th scope="col">Control</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row):?>
            <tr>
                <th scope="row"><img src="public\img\uploded\members\<?php echo $row["path"]?>" alt=""
                        style="height:15vh"></th>
                <th scope="row"><?php echo $row["username"]?></th>
                <td><?php echo $row["email"]?></td>
                <td><?php echo $row["created_at"]?></td>
                <td>
                    <a class="btn btn-info" href="?do=show&userid=<?php echo $row["user_id"]?>"><i
                            class="fas fa-eye"></i></a>
                    <?php if($_SESSION['GROUB_ID']== 1):?>
                    <a class="btn btn-warning" href="?do=edit&userid=<?php echo $row["user_id"]?>"><i
                            class="fas fa-edit"></i></a>
                    <a class="btn btn-danger" href="delete.php?userid=<?php echo $row["user_id"]?>"><i
                            class="fas fa-trash-alt"></i></a>
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <?php
        $stmt = $con->prepare("SELECT * FROM users WHERE groupid=1 ORDER BY user_id DESC");
        $stmt->execute();
        $total_record = $stmt->rowCount();
        $total_page = ceil($total_record / $recorded_per_page);
        $start_loop = 1;
        $end_loop = $total_page;
    ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php for($i =$start_loop; $i<=$end_loop; $i++):?>
            <li class="page-item"><a class="page-link" href="?do=manage&page=<?= $i?>"><?= $i?></a></li>
            <?php endfor?>
        </ul>
    </nav>
</div>
<?php elseif($do == "add"):?>
<div class="container">
    <h1 class="Add members"></h1>
    <form method="post" action="?do=insert" enctype="multipart/form-data">
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
        <div class="mb-3">
            <label for="formFile" class="form-label">Uploud Photo</label>
            <input class="form-control" type="file" id="formFile" name="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($do == "insert"):?>
<?php
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            // $avatar   = $_FILES['avatar'];
            $avatarName = $_FILES['avatar']['name'];
            $avatarType = $_FILES['avatar']['type'];
            $avatarTmpName = $_FILES['avatar']['tmp_name'];
            $avatarError = $_FILES['avatar']['error'];
            $avatarSize = $_FILES['avatar']['size'];

            $avatarAllowedExtension = array("image/jpeg" , "image/png" , "image/jpg");
            $avatar = rand(0 , 1000)."_".$avatarName;
            $destntion = "public\img\uploded\members\\".$avatar;
            if(in_array($avatarType , $avatarAllowedExtension)){
                move_uploaded_file($avatarTmpName , $destntion);
            }
            $username = $_POST['username'];
            $email    = $_POST['email'];
            $password = sha1($_POST['password']);
            $fullname = $_POST['fullname'];

            $formErrors = array();
            if(empty($username)){
                $formErrors[] = "username is empty";
            }
            if(strlen($username)< 4){
                $formErrors[] = "username is less than 4 c";
            }
            if(empty($formErrors)){
                $stmt = $con->prepare("INSERT INTO users (username,password,email,fullname,groupid,created_at,path) VALUES(?,?,?,?,0,now(),?)");
            $stmt->execute(array($username,$password,$email,$fullname,$avatar));
            header("location:members.php?do=add");
            }else{
                foreach($formErrors as $error){
                    echo $error . "<br>" ;
                }
            }
            
            
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
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $userid     = $_POST['userid'];
        $stmt = $con->prepare("DELETE FROM users WHERE user_id=");
        $stmt->execuet(array($userid));
        header("location:members.php");
    }
?>
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