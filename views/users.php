<?php
    include_once("../models/adminfunctions.php");
    
    GeneralFunctions::check_session();
    
    GeneralFunctions::check_menu_permission("users");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Users</title>
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
            
                
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-sm-8">
                        <h2>Users</h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="title-action">
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_user();">Add New</a>
                        </div>
                    </div>
                </div>
                
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ibox">
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th style="width:10px;">#</th>
                                                    <th>User</th>
                                                    <th>Username</th>
                                                    <th>Mobile</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th style="width:40px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $users = AdminFunctions::get_users();
                                                    
                                                    if(mysqli_num_rows($users) > 0)
                                                    {
                                                        $i = 1;
                                                        foreach($users as $user)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td align="center"><?php echo $i++; ?></td>
                                                                <td><?php echo $user['user_name']; ?></td>
                                                                <td><?php echo $user['username']; ?></td>
                                                                <td><?php echo $user['mobile_no']; ?></td>
                                                                <td align="center">
                                                                    <?php
                                                                        if($user['role'] == ADMIN)
                                                                        {
                                                                            echo "Admin";
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "Operator";
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td align="center">
                                                                    <?php
                                                                        if($user['active'] == YES)
                                                                        {
                                                                            ?><b class="text-success">Active</b><?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?><b class="text-danger">Inactive</b><?php
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td class="noExport" align="center">
                                                                    <div class="btn-group">
                                                                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                                        <ul class="dropdown-menu">
                                                                            <?php
                                                                                if($user['active'] == YES)
                                                                                {
                                                                                    ?>
                                                                                    <li><a class="dropdown-item" href="menupermsn.php?3LVyQ9v4bZORQR5=<?php echo base64_encode(base64_encode("uid=" . $user['id'])); ?>">Assign Permissions</a></li>
                                                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="reset_user_password(<?php echo $user['id']; ?>);">Reset Password</a></li>
                                                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="change_user_status(<?php echo $user['id']; ?>);">Deactivate A/c</a></li>
                                                                                    <?php
                                                                                }
                                                                                else
                                                                                {
                                                                                    ?>
                                                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="change_user_status(<?php echo $user['id']; ?>);">Activate A/c</a></li>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td align="center" colspan="7">Click On Add New Button</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog"  aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content loader-parent">
                            <div class="sk-spinner sk-spinner-double-bounce">
                                <div class="sk-double-bounce1"></div>
                                <div class="sk-double-bounce2"></div>
                            </div>
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Add User</h4>
                            </div>
                            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_user_login();">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="tel" class="form-control" id="mobile_no" name="mobile_no" placeholder="Mobile No." autocomplete="off" minlength="10" maxlength="10" onkeypress="return IsNumeric(event);" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="role" value="<?php echo ADMIN; ?>">&nbsp;Admin&nbsp;
                                        <input type="radio" name="role" value="<?php echo OPERATOR; ?>" checked="checked">&nbsp;Operator
                                    </div>
                                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("create_user_login"); ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
        <script src="<?php echo SERVER_BASE_URL; ?>assets/js/custom.js"></script>
        
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
            
            function add_user()
            {
                $("#user_name, #mobile_no").val("");
                $("input[name='role'][value='<?php echo OPERATOR; ?>']").prop("checked", true);
                $(".modal-title").html("Add User");
                $("#myModal").modal("show");
            }
            
            function create_user_login()
            {
                $('.modal-content').addClass('sk-loading');
                $find = $("#myForm").serialize();
                console.log(find);
                $.ajax({
                    url: "../controllers/admin.php",
                    method : "POST",
                    data: $("#myForm").serialize(),
                    success : function(data){
                        var response = $.parseJSON(data);
                        if(response.success == 1)
                        {
                            show_message(response.msg, 'Success', MSG_SUCCESS);
                            $("#myModal").modal("hide");
                            window.location.reload();
                        }
                        else
                        {
                            show_message(response.msg, 'Error', MSG_ERROR);
                            $('.modal-content').removeClass('sk-loading');
                        }
                    }
                });
            }
            
            function change_user_status(uid)
            {
                if(confirm("Are you sure?"))
                {
                    $('.modal-content').addClass('sk-loading');
                    $.ajax({
                        url: "../controllers/admin.php",
                        method : "POST",
                        data: { "uid" : uid, "c1e7c5cfd3d06ee8ef28b5c807d50f3b" : btoa("change_user_status") },
                        success : function(data){
                            var response = $.parseJSON(data);
                            if(response.success == 1)
                            {
                                show_message(response.msg, 'Success', MSG_SUCCESS);
                                window.location.reload();
                            }
                            else
                            {
                                show_message(response.msg, 'Error', MSG_ERROR);
                                $('.modal-content').removeClass('sk-loading');
                            }
                        }
                    });
                }
                else
                {
                    return false;
                }
            }
            
            function reset_user_password(uid)
            {
                if(confirm("Are you sure?"))
                {
                    $('.modal-content').addClass('sk-loading');
                    $.ajax({
                        url: "../controllers/admin.php",
                        method : "POST",
                        data: { "uid" : uid, "c1e7c5cfd3d06ee8ef28b5c807d50f3b" : btoa("reset_user_password") },
                        success : function(data){
                            var response = $.parseJSON(data);
                            if(response.success == 1)
                            {
                                show_message(response.msg, 'Success', MSG_SUCCESS);
                                window.location.reload();
                            }
                            else
                            {
                                show_message(response.msg, 'Error', MSG_ERROR);
                                $('.modal-content').removeClass('sk-loading');
                            }
                        }
                    });
                }
                else
                {
                    return false;
                }
            }
        </script>
    </body>
</html>