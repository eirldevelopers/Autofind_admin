<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/dashboardfunctions.php"); ?>
<?php   $data = DashbardFunctions::get_dashboard_data(); ?>


<script>
    $(function() {
        <?php
        if (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] > 0) {
        ?>show_message('<?php echo $_SESSION['msg']; ?>', '', <?php echo $_SESSION['msg_type']; ?>);
    <?php
            $_SESSION['msg_type'] = 0;
            $_SESSION['msg'] = "";
        }
    ?>
    });
</script>

<div class="wrapper wrapper-content"></div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="row">
                    <?php if($_SESSION['role']!='3')
                    {?>
                    <div class="col-md-6 col-lg-3">
                        <div class="widget-small primary coloured-icon"><i class="icon fa fa-3x"><?php echo $data['users'];?></i>
                            <div class="info">
                                <h4>TOTAL USERS</h4>
                                <p><b><a href="users.php">View all</a></b></p>
                            </div>
                        </div>
                    </div>
                   <?php } ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="widget-small info coloured-icon"><i class="icon fa fa-3x"><?php echo $data['products'];?></i>
                            <div class="info">
                                <h4>TOTAL PRODUCTS</h4>
                                <p><b><a href="products.php">View all</a></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="widget-small warning coloured-icon"><i class="icon fa fa-3x"><?php echo $data['total_orders'];?></i>
                            <div class="info">
                                <h4>TOTAL ORDERS</h4>
                                <p><b><a href="orders.php">View all</a></b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="widget-small info coloured-icon"><i class="icon fa fa-3x"><?php echo $data['pending_orders'];?></i>
                            <div class="info">
                                <h4>PENDING ORDERS</h4>
                                <p><b><a href="orders.php">View all</a></b></p>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6 col-lg-3">
                        <div class="widget-small info coloured-icon"><i class="icon fa fa-3x"><?php echo $data['delivered_orders'];?></i>
                            <div class="info">
                                <h4>DELIVERED ORDERS</h4>
                                <p><b><a href="orders.php">View all</a></b></p>
                            </div>
                        </div>
                    </div>                    
                </div>
    </div>
</div>


<?php include_once(__DIR__ . "/../footer.php"); ?>