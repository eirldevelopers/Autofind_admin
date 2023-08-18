<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/categoryfunctions.php"); ?>

<div class="modal inmodal fade" id="myCategory5Modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_category5();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group selection">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="l5_category" name="l5_category" placeholder="Sub Category Name" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="image_file">Upload Image</label>
                        <input type="file" class="form-control-file" id="image_file" name="image_file" accept="image/*" />
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("create_category5"); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal inmodal fade" id="EditCategory5Modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <form role="form" id="editForm" method="post" onsubmit="event.preventDefault(); update_category5();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group selection">
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="category_five_id" name="category_five_id" autocomplete="off" />
                        <input type="text" class="form-control" id="l_five_category" name="l_five_category" placeholder="Sub Category Name" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="image_file">Upload Image</label>
                        <input type="file" class="form-control-file" id="image_file_one" name="image_file_one" accept="image/*" />
                        <div class="show-images mt-3"></div>
                        
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("update_category5"); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>Category5</h2>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_category5();">Add New</a>
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
                                    <th style="width: 10px">#</th>
                                    <th>Category5</th>
                                    <th>Category4</th>
                                    <th>image</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = CategoryFunctions::get_category5();

                                if (
                                    mysqli_num_rows($users) > 0
                                ) {
                                    $i = 1;
                                    foreach ($users as $user) { ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td><?php echo $user['l5_category']; ?></td>
                                            <td><?php echo $user['l4_category']; ?></td>

                                            <td>
                                                <?php if(!empty($user['image_file'])) { ?>
                                                    <img src="<?php echo IMAGE_URL .'category/'. $user['image_file']; ?>" alt="category image" width="50" height="50"></td>
                                                    <td align="center">    
                                                <?php } else {   ?> 
                                                    <img src="<?php echo IMAGE_URL.'logo1.png'; ?>" alt="category image" width="50" height="50"></td>
                                                    <td align="center">
                                                <?php
                                                }
                                                if ($user['active'] == '1') {
                                                    ?>
                                                    <input type="checkbox" id="category_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_category5_status(this);" checked>
                                                    <label for="category_<?php echo $user['id'] ?>"></label>
                                                    <?php
                                                } else { ?>
                                                    <input type="checkbox" id="category_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_category5_status(this);">
                                                    <label for="category_<?php echo $user['id'] ?>"></label>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td class="noExport" align="center">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                    <ul class="dropdown-menu" style="width: auto;">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="edit_category5(<?php echo $user['id']; ?>,<?php echo $user['cid']; ?>);">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="delete_category5(<?php echo $user['id']; ?>);">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
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


<?php include_once(__DIR__ . "/../footer.php"); ?>