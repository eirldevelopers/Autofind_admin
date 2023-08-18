<?php
    include_once("../models/superuserfunctions.php");
    
    GeneralFunctions::check_session();
    
    GeneralFunctions::check_menu_permission("menus");
    
    $menu_options = "<option value='0'> - Select Parent - </option>";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php echo SITE_NAME; ?> | Menus</title>
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
                        <h2>Menus</h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="title-action">
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_menu();">Add New</a>
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
                                                    <th>Menu</th>
                                                    <th>Link</th>
                                                    <th style="width:40px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $menus = SuperuserFunctions::get_all_menus();
                                                    
                                                    if(count($menus) > 0)
                                                    {
                                                        $i = 1;
                                                        foreach($menus as $menu)
                                                        {
                                                            $menu_options .= "<option value='" . $menu->id . "'>" . $menu->menu . "</option>";
                                                            ?>
                                                            <tr class="parentmenu">
                                                                <td><?php echo intval($menu->m_order); ?></td>
                                                                <td><?php echo $menu->menu; ?></td>
                                                                <td><?php echo ($menu->link == "#" ? "" : $menu->link); ?></td>
                                                                <td class="noExport" align="center">
                                                                    <div class="btn-group">
                                                                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                                        <ul class="dropdown-menu">
                                                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="edit_menu(<?php echo $menu->id; ?>, <?php echo $menu->pid; ?>, '<?php echo $menu->menu; ?>', '<?php echo $menu->link; ?>', '<?php echo $menu->level; ?>', <?php echo $menu->m_order; ?>);">Edit</a></li>
                                                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="delete_menu(<?php echo $menu->id; ?>, <?php echo $menu->pid; ?>);">Delete</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            
                                                            if(count($menu->children) > 0)
                                                            {
                                                                foreach($menu->children as $child)
                                                                {
                                                                    ?>
                                                                    <tr class="childmenu">
                                                                        <td><?php echo intval($menu->m_order) . "." . intval($child->m_order); ?></td>
                                                                        <td><?php echo $child->menu; ?></td>
                                                                        <td><?php echo ($child->link == "#" ? "" : $child->link); ?></td>
                                                                        <td class="noExport" align="center">
                                                                            <div class="btn-group">
                                                                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                                                <ul class="dropdown-menu">
                                                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="edit_menu(<?php echo $child->id; ?>, <?php echo $child->pid; ?>, '<?php echo $child->menu; ?>', '<?php echo $child->link; ?>', '<?php echo $child->level; ?>', <?php echo $child->m_order; ?>);">Edit</a></li>
                                                                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="delete_menu(<?php echo $child->id; ?>, <?php echo $child->pid; ?>);">Delete</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td align="center" colspan="4">Click On Add New Button</td>
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
                                <h4 class="modal-title">Add Menu</h4>
                            </div>
                            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); save_menu();">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu" required autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control chosen-select" id="parent_id" name="parent_id" autocomplete="off" data-placeholder="Select Parent">
                                            <?php echo $menu_options; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="link" name="link" placeholder="Link" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="m_order" name="m_order" placeholder="Order" onkeypress="return IsDecimal(event);" autocomplete="off" required>
                                    </div>
                                    <input type="hidden" name="mid" id="mid">
                                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("save_menu"); ?>">
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
            
            function add_menu()
            {
                $("#menu, #link, #m_order").val("");
                $("#parent_id").chosen("destroy");
                $("#parent_id").val(0);
                $("#parent_id").chosen({"allow_single_deselect": true,width: "100%"});
                $("#mid").val(0);
                $(".modal-title").html("Add Menu");
                $("#myModal").modal("show");
            }
            
            function edit_menu(mid, pid, menu, link, level, m_order)
            {
                $("#menu").val(menu);
                $("#link").val(link);
                $("#m_order").val(m_order);
                $("#parent_id").chosen("destroy");
                $("#parent_id").val(pid);
                $("#parent_id").chosen({"allow_single_deselect": true,width: "100%"});
                $("#mid").val(mid);
                $(".modal-title").html("Edit Menu");
                $("#myModal").modal("show");
            }
            
            function save_menu()
            {
                $('.modal-content').addClass('sk-loading');
                $.ajax({
                    url: "../controllers/superuser.php",
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
            
            function delete_menu(mid, pid)
            {
                var confirmMsg = "Are you sure to delete the menu and its child menu?";
                if(pid > 0)
                {
                    confirmMsg = "Are you sure to delete the menu?";    
                }
                
                if(confirm(confirmMsg))
                {
                    $('.modal-content').addClass('sk-loading');
                    $.ajax({
                        url: "../controllers/superuser.php",
                        method : "POST",
                        data: { "mid" : mid, "pid" : pid, "c1e7c5cfd3d06ee8ef28b5c807d50f3b" : btoa("delete_menu") },
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