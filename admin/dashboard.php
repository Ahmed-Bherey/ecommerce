<?php session_start()?>
<?php if(isset($_SESSION['USER_NAME'])):?>
    <?php include "resources/function.php"?>
    <?php include "resources/includes/header.inc"?>
    <?php require "config.php"?>
    <?php include "resources/includes/navbar.inc"?>
    

    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="members">
                    <p>All Members</p>
                    <i class="fas fa-users icon" title="Members"></i>
                    (<?php echo countItem("user_id" , "users" , "groupid=0")?>)
                </div>
            </div><div class="col-6">
                <div class="cats">
                <p>All Categories</p>
                <i class="fas fa-shopping-cart icon" title="Category"></i>
                    (<?php echo countItem("cat_id " , "cats")?>)
                </div>
            </div>
        </div>
    </div>

    <?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php") ?>
<?php endif?>