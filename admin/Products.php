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

<?php if($do = "manage"):?>

<?php elseif($do == "add"):?>

<?php elseif($do == "insert"):?>

<?php elseif($do == "edit"):?>

<?php elseif($do == "update"):?>

<?php elseif($do == "delete"):?>

<?php elseif($do == "show"):?>

<?php endif?>

<?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php")?>
<?php endif?>