<?php
    include_once("models/generalfunctions.php");
    
    session_start();
    if(isset($_SESSION['tns_user']))
    {
        header("Location: views/seller_dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Login</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    </head> 

    <body class="gray-bg">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content">
                        <!-- <img class="mt-1" src="<?php echo SERVER_BASE_URL; ?>assets/img/logo.png" style="width:50%;margin:-30px 25%;"> -->
                        <h3 class="font-bold mt-4">Welcome to <?php echo SITE_NAME; ?></h3>
                        <form class="m-t" role="form" action="controllers/general.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                            </div>
                            <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" id="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("user_login");?>">
                            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                            <!--<a href="forgotpassword.php">
                                <small>Forgot password?</small>
                            </a>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/project.js"></script>
        
        <script>
            $(function(){
                <?php
                    if(isset($_SESSION['login_error']) && $_SESSION['login_error'] != "")
                    {
                        ?>show_message('<?php echo $_SESSION['login_error']; ?>', 'Error', <?php echo MSG_ERROR; ?>);<?php
                        $_SESSION['login_error'] = "";
                    }
                ?>
            });
        </script>
    </body>
</html>