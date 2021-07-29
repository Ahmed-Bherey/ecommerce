<?php session_start()?>
<?php if(isset($_SESSION['USER_NAME'])):?>
    <?php include "resources/function.php"?>
    <?php include "resources/includes/header.inc"?>
    <?php require "config.php"?>
    <?php include "resources/includes/navbar.inc"?>

    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="members">
                    <i class="fas fa-users"></i>
                    <?php echo countItem("user_id" , "users")?>
                </div>
            </div>
        </div>
    </div>

    <?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php") ?>
<?php endif?>