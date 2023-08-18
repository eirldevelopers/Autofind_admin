<?php 
@header('Access-Control-Allow-Origin: *');
@header('Access-Control-Allow-Methods: GET, POST');
@header("Access-Control-Allow-Headers: X-Requested-With");
?>
<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ . "/../models/productfunctions.php"); ?>

<div class="modal inmodal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_product();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control l2c_id" id="l2c_id" name="l2c_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control" id="unit" name="unit" onkeydown="checkNumberOnly(event);" placeholder="Enter Unit In Grams" autocomplete="off" maxlength="5" required />

                                <input type="hidden" name="l1c_id" value="1" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_image">Upload Image</label>
                        <input type="file" class="form-control-file" id="add_product_image" name="product_image" accept="image/*" />
                        <div id="add_new_product_img"> </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Short Description</label>
                        <textarea class="form-control" name="description" maxlength="1000" id="description" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("create_product"); ?>">
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

<div class="modal inmodal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content loader-parent">
            <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <form role="form" id="editForm" method="post" onsubmit="event.preventDefault(); update_product();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control product_name" id="product_name" name="product_name" placeholder="Enter Product Name" autocomplete="off" required />
                                <input type="hidden" class="form-control" id="product_id" name="product_id" required />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select class="form-control edit_l2c_id" id="edit_l2c_id" name="edit_l2c_id" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input type="text" class="form-control units" id="unit" name="unit" onkeydown="checkNumberOnly(event);" placeholder="Enter Unit In Grams" autocomplete="off" maxlength="5" required />

                                <input type="hidden" name="l1c_id" value="1" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="product_image">Upload Image</label>
                                    <input type="file" class="form-control" id="product_img" name="product_image" accept="image/*" />
                                    <div id="new_product_img"> </div>
                                </div>
                            </div>
                            <div class="col-sm-6 product_img"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Short Description</label>
                        <textarea class="form-control description" name="description" maxlength="1000" id="description" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("update_product"); ?>">
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
        <h2>Products</h2>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_product();">Add New</a>
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
                                    <th>Category</th>
                                 <!--    <th>Category2</th> -->
                                    <th>Product</th>
                                    <th>image</th>
                                    <th>Weight (In Grams)</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $products = ProductFunctions::get_all_products();
                                if ($products) {
                                    $i = 1;
                                    foreach ($products as $user) { ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td><?php echo $user['l1_category']; ?></td>
                                           <!--  <td><?php //echo $user['l2_category']; ?></td> -->
                                            <td><?php echo $user['product_name']; ?></td>
                                            
                                                <?php //if (!empty($user['product_image'])) { ?>
                                                    <td>
                                                    <img src="<?php echo PRODUCT_URL.'product/'.$user['product_image']; ?>" alt="product image" width="50" height="50">
                                            </td>
                                            <?php //} else {   ?>
                                               <!--  <td align="center">
                                                    <img src="<?php //echo PRODUCT_URL . 'logo1.png'; ?>" alt="category image" width="50" height="50">
                                                </td> -->
                                            <?php //} ?>
                                            <td width="200">
                                                <span class="label label-primary"><?php echo $user['unit_name']; ?></span>
                                            </td>
                                            <td align="center">
                                            <?php
                                                if ($user['available'] == '1') {
                                            ?>
                                                <input type="checkbox" id="product_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_product_status(this);" checked>
                                                <label for="product_<?php echo $user['id'] ?>"></label>
                                            <?php
                                                } else { ?>
                                                <input type="checkbox" id="product_<?php echo $user['id'] ?>" data-itemid="<?php echo $user['id'] ?>" onchange="change_product_status(this);">
                                                <label for="product_<?php echo $user['id'] ?>"></label>
                                            <?php
                                                }
                                            ?>
                                            </td>
                                            <td class="noExport" align="center">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                    <ul class="dropdown-menu" style="width: auto;">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="edit_product(<?php echo $user['id']; ?>,<?php echo $user['cid']; ?>);">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="delete_product(<?php echo $user['id']; ?>);">Delete</a>
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

