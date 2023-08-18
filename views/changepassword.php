<?php
    include_once("../models/generalfunctions.php");
    
    GeneralFunctions::check_session();
    
    GeneralFunctions::check_menu_permission("changepassword");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Change Password</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    </head>
    <body class="md-skin">
        <div id="wrapper">

            <?php include_once("../sidebar.php"); ?>
            
            <div id="page-wrapper" class="gray-bg">
            
                <?php include_once("../header.php"); ?>
                
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-sm-4">
                        <h2>Change Password</h2>
                    </div>
                </div>
            
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="ibox">
                                <div class="ibox-content">
                                    <form role="form" onsubmit="return change_password();" id="cpForm" method="post" action="../controllers/general.php">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">New Password</label>
                                            <div class="col-lg-9"><input type="password" id="password" name="password" placeholder="Enter Password" class="form-control" required></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Confirm Password</label>
                                            <div class="col-lg-9"><input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" class="form-control" required></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("change_password"); ?>">
                                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php include_once("../footer.php"); ?>

            </div>
        </div>

        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/plugins/pace/pace.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/idle-timer/idle-timer.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/app.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/project.js"></script>
        
        <script>
            $(function(){
                <?php
                    if(isset($_SESSION['msg_type']) && $_SESSION['msg_type'] > 0)
                    {
                        ?>show_message('<?php echo $_SESSION['msg']; ?>', '', <?php echo $_SESSION['msg_type']; ?>);<?php
                        $_SESSION['msg_type'] = 0;
                        $_SESSION['msg'] = "";
                    }
                ?>
                
                setTimeout(function(){
                    $("#password").val("");
                }, 10);
            });
            
            function change_password()
            {
                if($("#password").val() != $("#cpassword").val())
                {
                    alert("Password does not match!!!");
                    return false;
                }
                
                $("cpForm").submit();
                return true;
            }
        </script>
    </body>
</html>