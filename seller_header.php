<?php
    include_once("../models/generalfunctions.php");
    
    GeneralFunctions::check_session();
    
    GeneralFunctions::check_menu_permission("dashboard");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Dashboard</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">
        <!-- style sheet -->
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/datatables.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/datepicker/datepicker.css" rel="stylesheet">
        <!-- style sheet -->
        
        <!-- jquery -->
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/jquery-3.1.1.min.js"></script>
        <!-- jquery -->
    </head>


        <body class="md-skin">
        <div id="wrapper" class="loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>