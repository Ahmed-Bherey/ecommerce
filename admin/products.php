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
    $stmt = $con->prepare("SELECT products.* , cats.cat_name FROM products INNER JOIN cats ON cats.cat_id=products.cat_id");
    $stmt->execute();
    $rows = $stmt->fetchAll();
?>
<div class="container">
    <h1 class="text-center">All products</h1>
    <a href="?do=add" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add members</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Product Name</th>
                <th scope="col">Product Price</th>
                <th scope="col">Desc</th>
                <th scope="col">Category</th>
                <th scope="col">Contrils</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row):?>
            <tr>
                <th scope="row"><?= $row['productname']?></th>
                <td><?= $row['price']?></td>
                <td><?= $row['description']?></td>
                <td><?= $row['cat_name']?></td>
                <td>
                    <a class="btn btn-info" href="?do=show&productid=<?= $row['product_id']?>"><i
                            class="fas fa-eye"></i></a>
                    <a class="btn btn-warning" href="?do=edit&productid=<?= $row['product_id']?>"><i
                            class="fas fa-edit"></i></a>
                    <a class="btn btn-danger" href="?do=delete&productid=<?= $row['product_id']?>"><i
                            class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php elseif($do == "add"):?>
<div class="container">
    <h1 class="text-center">Add Products</h1>
    <form method="post" action="?do=insert" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Product Name</label>
            <input type="text" class="form-control" name="productname">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Product Price</label>
            <input type="number" class="form-control" name="productprice">
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Desc</label>
                <input type="text" class="form-control" name="desc">
            </div>
            <?php
                $stmt = $con->prepare("SELECT * FROM cats");
                $stmt->execute();
                $rows = $stmt->fetchAll();
            ?>
            <select class="form-select" name="category">
                <option selected>Select Category</option>
                <?php foreach($rows as $row):?>
                <option value="<?= $row['cat_id']?>"><?= $row['cat_name']?></option>
                <?php endforeach?>
            </select>
            <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php elseif($do == "insert"):?>
<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $productname  = $_POST['productname'];
        $productprice = $_POST['productprice'];
        $descc         = $_POST['desc'];
        $category        = $_POST['category'];

                $stmt = $con->prepare("INSERT INTO products (productname,price,description,cat_id) VALUES(?,?,?,?)");
                $stmt->execute(array($productname,$productprice,$descc,$category));
                header("location:products.php");
    }
?>
<?php elseif($do == "edit"):?>

<?php elseif($do == "update"):?>

<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>

<?php endif?>

<?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php")?>
<?php endif?>