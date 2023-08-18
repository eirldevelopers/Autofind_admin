<?php
    include_once("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | 404 Error</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
    </head>

    <body class="gray-bg">
        <div class="middle-box text-center animated fadeInDown">
            <h1>404</h1>
            <h3 class="font-bold">Page Not Found</h3>

            <div class="error-desc">
                Sorry, but the page you are looking for has not been found. Try checking the URL for error, then hit the refresh button on your browser or go to home page in our app.
            </div>
            <br />
            <button type="submit" class="btn btn-primary" onclick="window.location.assign('index.php');">Home Page</button>
        </div>

        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/bootstrap.js"></script>
    </body>
</html>