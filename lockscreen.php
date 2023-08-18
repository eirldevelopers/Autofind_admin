<?php
    include_once("models/generalfunctions.php");
    
    session_start();
    if(isset($_SESSION['tns_user']))
    {
        if($_SESSION['locked'] == 0)
        {
            //header("Location: views/dashboard.php");
            header("Location: " . base64_decode($_GET['rurl']));
            exit;
        }
    }
    else
    {
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo SITE_NAME; ?> | Lockscreen</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    </head>
    <body class="gray-bg">

        <div class="lock-word animated fadeInDown">
            <span class="first-word">LOCKED</span><span>SCREEN</span>
        </div>
        <div class="middle-box text-center lockscreen animated fadeInDown">
            <div>
                <div class="m-b-md">
                    <i class="fa fa-user fa-5x"></i>
                </div>
                <h3><?php echo $_SESSION['username']; ?></h3>
                <p>Your are in lock screen. Enter your password to retrieve your session.</p>
                <form class="m-t" role="form" method="post" action="unlock_access.php">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="******" required="">
                        <input type="hidden" name="rurl" value="<?php echo $_GET['rurl']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary block full-width">Unlock</button>
                </form>
            </div>
            <div>
                <a href="switch_user_account.php">Or<br />sign in as a different user</a>
            </div>
        </div>

        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.js"></script>
        
        <script>
            toastr.options = {
              "closeButton": true,
              "debug": false,
              "progressBar": false,
              "preventDuplicates": false,
              "positionClass": "toast-top-center",
              "onclick": null,
              "showDuration": "400",
              "hideDuration": "1000",
              "timeOut": "8000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            
            $(function(){
                <?php
                    if(isset($_SESSION['login_error']) && $_SESSION['login_error'] != "")
                    {
                        ?>toastr.error('<?php echo $_SESSION['login_error']; ?>', 'Error');<?php
                        $_SESSION['login_error'] = "";
                    }
                ?>
                
                setInterval(function(){
                    $.ajax({
                        url: "check_session.php",
                        method: "POST",
                        success: function(data){
                            response = $.parseJSON(data);
                            if(response.status == 0)
                            {
                                window.location.href = "index.php";
                            }
                            else if(response.status == 1)
                            {
                                window.location.href = atob('<?php echo $_GET['rurl']; ?>');
                            }
                        }
                    });
                }, 10000);
            });
        </script>

    </body>
</html>