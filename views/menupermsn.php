<?php
    include_once("../models/adminfunctions.php");
    
    GeneralFunctions::check_session();
    
    GeneralFunctions::check_menu_permission("users");
    
    $token = $_GET['3LVyQ9v4bZORQR5'];
    $token = base64_decode(base64_decode($token));
    
    $parameters = explode("&", $token);
    
    foreach($parameters as $value)
    {
        $param = explode("=", $value);
        ${$param[0]} = $param[1];
    }
    
    $user = AdminFunctions::get_users($uid);
    $user = mysqli_fetch_assoc($user);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Assign Permissions</title>
        <link rel="shortcut icon" href="<?php echo SERVER_BASE_URL; ?>assets/img/favicon.ico" type="image/x-icon">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/custom.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/colorbox/colorbox.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/datepicker/datepicker.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/datatables.min.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
        <link href="<?php echo SERVER_BASE_URL; ?>assets/css/file-upload.css" rel="stylesheet">
    </head>
    <body class="md-skin">
        <div id="wrapper" class="loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>

            <?php include_once("../sidebar.php"); ?>
            
            <div id="page-wrapper" class="gray-bg">
            
                <?php include_once("../header.php"); ?>
                
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-sm-8">
                        <h2>Assign Permissions to <?php echo $user['user_name']; ?></h2>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
                
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox">
                                <div class="ibox-content">
                                    <form role="form" method="post" action="../controllers/admin.php">
                                        <div class="box-body">
                                            <?php
                                                $menus = AdminFunctions::get_all_menus();
                                                $permissions = AdminFunctions::get_menu_permissions($uid);
                                                
                                                if(count($menus) > 0)
                                                {
                                                    ?>
                                                    <table class="table table-sm table-hover table-bordered">
                                                        <?php
                                                            foreach($menus as $menu)
                                                            {
                                                                $checked = (in_array($menu->id, $permissions) ? 1 : 0);
                                                                ?>
                                                                <tr style="background-color:#E6FFE6;">
                                                                    <td style="width:40px;" align="left"><input type="checkbox" name="menuid[]" value="<?php echo $menu->id; ?>" <?php echo ($checked ? "checked='checked'" : ""); ?> menuid="<?php echo $menu->id; ?>" id="parentmenu<?php echo $menu->id; ?>" class="parentmenu"></td>
                                                                    <td><?php echo $menu->menu; ?></td>
                                                                </tr>
                                                                <?php
                                                                    if(count($menu->children) > 0)
                                                                    {
                                                                        foreach($menu->children as $child)
                                                                        {
                                                                            $checked = (in_array($child->id, $permissions) ? 1 : 0);
                                                                            ?>  
                                                                            <tr>
                                                                                <td style="width:40px;" align="right"><input type="checkbox" name="menuid[]" value="<?php echo $child->id; ?>" <?php echo ($checked ? "checked='checked'" : ""); ?> parentid="<?php echo $menu->id; ?>" class="childmenu<?php echo $menu->id; ?> childmenu"></td>
                                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child->menu; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    }
                                                                ?>
                                                                <?php
                                                            }
                                                        ?>
                                                    </table>
                                                    <?php
                                                }
                                                else
                                                {
                                                    echo "Menus not found.";
                                                }
                                            ?>
                                        </div>
                                        <div class="box-footer">
                                            <?php
                                                if(count($menus) > 0)
                                                {
                                                    ?>
                                                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
                                                    <input type="hidden" name="utype" value="<?php if(isset($_GET['utype']) && $_GET['utype']==2){echo "2"; }else{ echo "1"; } ?>">
                                                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("save_menu_permission"); ?>">
                                                    <input type="submit" class="btn btn-primary btn-sm" value="Save">
                                                    <input type="button" class="btn btn-default btn-sm" value="Cancel" onclick="history.go('-1');">
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <br /><input type="button" class="btn btn-default btn-sm" value="Cancel" onclick="history.go('-1');">
                                                    <?php
                                                }
                                            ?>
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
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/colorbox/jquery.colorbox-min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/chosen/chosen.jquery.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/datepicker/datepicker.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/datatables.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo SERVER_BASE_URL; ?>assets/plugins/chosen/chosen.jquery.js"></script>
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
            });
            
            $(document).on("click", ".parentmenu", function(){
                menuid = $(this).attr("menuid");
                if($(this).is(":checked"))
                {
                    $(".childmenu" + menuid).prop("checked", true);
                }
                else
                {
                    $(".childmenu" + menuid).prop("checked", false);
                }
            });
            
            $(document).on("click", ".childmenu", function(){
                parentid = $(this).attr("parentid");
                if($(".childmenu" + parentid + ":checked").length == 0)
                {
                    $("#parentmenu" + parentid).prop("checked", false);
                }
                else
                {
                    $("#parentmenu" + parentid).prop("checked", true);
                }
            });
        </script>
    </body>
</html>