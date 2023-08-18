<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/categoryfunctions.php"); ?>

<div class="modal inmodal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_category1();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="l1_category" name="l1_category" placeholder="Category Name" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="image_file">Upload Image</label>
                        <input type="file" class="form-control-file" id="image_file" name="image_file" accept="image/*" />
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("create_category1"); ?>">
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


<div class="modal inmodal fade" id="EditModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                <h4 class="modal-title">Add Category</h4>
            </div>
            <form role="form" id="editForm" method="post" onsubmit="event.preventDefault(); update_category1();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="category_one_id" name="category_one_id" autocomplete="off" />
                        <input type="text" class="form-control" id="l_one_category" name="l_one_category" placeholder="Category Name" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label for="image_file">Upload Image</label>
                        <input type="file" class="form-control-file" id="image_file_one" name="image_file_one" accept="image/*" />
                        <div class="show-images mt-3"></div>
                        
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("update_category1"); ?>">
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
        <h2>Category1</h2>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_category1();">Add New</a>
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
                                    <th>Category1</th>
                                    <th>image</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = CategoryFunctions::get_category1();

                                if (
                                    mysqli_num_rows($users) >
                                    0
                                ) {
                                    $i = 1;
                                    foreach ($users as $user) { ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td><?php echo $user['l1_category']; ?></td>

                                            <td>
                                                <?php if(!empty($user['image_file'])) { ?>
                                                    <img src="<?php echo IMAGE_URL .'uploads/categories/'. $user['image_file']; ?>" alt="category image" width="50" height="50"></td>
                                                    <td align="center">    
                                                <?php } else {   ?> 
                                                    <img src="<?php echo IMAGE_URL.'uploads/categories/logo1.png'; ?>" alt="category image" width="50" height="50"></td>
                                                    <td align="center">
                                                <?php
                                                }
                                                if ($user['active'] == '1') {
                                                    ?>
                                                    <input type="checkbox" id="category_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_category1_status(this);" checked>
                                                    <label for="category_<?php echo $user['id'] ?>"></label>
                                                    <?php
                                                } else { ?>
                                                    <input type="checkbox" id="category_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_category1_status(this);">
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
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="edit_category1(<?php echo $user['id']; ?>);">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="delete_category1(<?php echo $user['id']; ?>);">Delete</a>
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