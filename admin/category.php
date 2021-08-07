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
$recorded_per_page = 5;
$page = isset($_GET['page'])?$_GET['page']:1;
$start_from = ($page-1)* $recorded_per_page;
        $stmt= $con->prepare("SELECT * FROM cats LIMIT $start_from , $recorded_per_page");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
<div class="container">
    <h1 class="text-center">All Products</h1>
    <a href="?do=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Product</a>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">ID</th>
                <th scope="col">UserName</th>
                <th scope="col">Price</th>
                <th scope="col">Date</th>
                <th scope="col">Control</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row):?>
            <tr>
            <th scope="row"><?php echo $row["cats_id"]?></th>
                <th scope="row"><?php echo $row["cat_name"]?></th>
                <td><?php echo $row["cat_price"]?></td>
                <td><?php echo $row["created_at"]?></td>
                <td>
                    <a class="btn btn-info" href="products.php?do=show&catsid=<?= $row["cats_id"]?>"
                        title="Show"><i class="fas fa-eye"></i></a>
                    <a class="btn btn-warning" href="Products.php?do=edit&catsid=<?= $row["cats_id"]?>"
                        title="Edit"><i class="fas fa-edit"></i></a>
                    <a class="btn btn-danger" href="Products.php?do=delete&catsid=<?= $row["cats_id"]?>"
                        title="Delete"><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <?php
        $stmt = $con->prepare("SELECT * FROM users WHERE groupid=0 ORDER BY user_id DESC");
        $stmt->execute();
        $total_record = $stmt->rowCount();
        $total_page = ceil($total_record / $recorded_per_page);
        $start_loop = 1;
        $end_loop = $total_page;
    ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
        <?php for($i =$start_loop; $i<=$end_loop; $i++):?>
        <li class="page-item"><a class="page-link" href="#"><?php echo $i?></a></li>
        <?php endfor?>
        </ul>
    </nav>
</div>
<?php elseif($do == "add"):?>

<?php elseif($do == "edit"):?>
<?php
    $catsid = isset($_GET['catsid'])&&is_numeric($_GET['catsid'])?intval($_GET['catsid']):0;
    $stmt = $con->prepare("SELECT * FROM cats WHERE cats_id=?");
    $stmt->execute(array($catsid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
?>
<?php if($count == 1):?>
<div class="container">
    <h1 class="text-center">Edit Products</h1>
    <form method="post" action="?do=update">
    <div class="mb-3">
            <input type="text" class="form-control" value="<?= $row['cats_id']?>" name="catsid">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Product Name</label>
            <input type="text" class="form-control" value="<?= $row['cat_name']?>" name="catname">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Product Price</label>
            <input type="text" class="form-control" value="<?= $row['cat_price']?>" name="catprice">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<!-- <?php endif?> -->
<?php elseif($do == "update"):?>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $catsid    = $_POST['catsid'];
        $catname  = $_POST['catname'];
        $catprice = $_POST['catprice'];
        $stmt = $con->prepare("UPDATE cats SET cat_name=? , cat_price=? WHERE cats_id=?");
        $stmt->execute(array($catname,$catprice,$catsid));
    }
?>
<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>
<?php 
        $productid = $_GET["catsid"] ;
        $stmt= $con->prepare("SELECT * FROM cats WHERE cats_id=?");
        $stmt->execute(array($productid));
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