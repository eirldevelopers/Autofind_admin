<?php
    include_once("models/generalfunctions.php");
    
    session_start();
    if(isset($_SESSION['tns_user']))
    {
        header("Location: views/dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Forgot Password</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
    </head>

    <body class="gray-bg">
        <div class="passwordBox animated fadeInDown">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content">
                        <h2 class="font-bold">Forgot password</h2>
                        <p>Enter your mobile number and your password will be reset and messaged to you.</p>

                        <div class="row">
                            <div class="col-lg-12">
                                <form class="m-t" role="form" onsubmit="send_new_password(); return false;">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="mobile_no" placeholder="Mobile Number" required minlength="10" maxlength="10" onkeypress="return IsNumeric(event);">
                                    </div>
                                    <button type="submit" class="btn btn-primary block full-width m-b">Send new password</button>
                                    
                                    <a href="index.php">
                                        <small>Click here to Login</small>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-8">
                    Copyright Engineering Innovations P Ltd
                </div>
                <div class="col-md-4 text-right">
                   <small>© 2018</small>
                </div>
            </div>
        </div>
        
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/project.js"></script>
        
        <script>
            function send_new_password()
            {
                $.ajax({
                    url: "controllers/general.php",
                    method: "POST",
                    data: { "mobile_no" : $("#mobile_no").val(), "c1e7c5cfd3d06ee8ef28b5c807d50f3b" : btoa("send_new_password") },
                    success: function(data){
                        response = $.parseJSON(data);
                        if(response.success == 1)
                        {
                            show_message(response.msg, 'Success', <?php echo MSG_SUCCESS; ?>);
                        }
                        else
                        {
                            show_message(response.msg, 'Error', <?php echo MSG_ERROR; ?>);
                        }
                    }
                });
            }
        </script>
    </body>
</html>