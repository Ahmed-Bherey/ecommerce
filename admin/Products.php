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
        $stmt= $con->prepare("SELECT * FROM products");
        $stmt->execute();
        $rows = $stmt->fetchAll();
    ?>
<div class="container">
    <h1 class="text-center">All Products</h1>
    <a href="?do=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Product</a>
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
                <th scope="row"><?php echo $row["name"]?></th>
                <td><?php echo $row["price"]?></td>
                <td><?php echo $row["created_at"]?></td>
                <td>
                    <a class="btn btn-secondary" href="products.php?do=show&productid=<?= $row["product_id"]?>">
                    <i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php elseif($do == "add"):?>

<?php elseif($do == "insert"):?>

<?php elseif($do == "edit"):?>

<?php elseif($do == "update"):?>

<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>
<?php 
        $productid = $_GET["productid"] ;
        $stmt= $con->prepare("SELECT * FROM products WHERE product_id=?");
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