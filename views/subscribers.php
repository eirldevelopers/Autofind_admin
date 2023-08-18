<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ . "/../models/productfunctions.php"); ?>


<div class="wrapper wrapper-content"></div>

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
                                    <th>Category2</th>
                                    <th>Product</th>
                                    <th>image</th>
                                    <th>Weight (In Grams)</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $products = ProductFunctions::get_all_subscribers();
                                if ($products) {
                                    $i = 1;
                                    foreach ($products as $user) { ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td><?php echo $user['l1_category']; ?></td>
                                            <td><?php echo $user['l2_category']; ?></td>
                                            <td><?php echo $user['product_name']; ?></td>
                                            <td>
                                                <?php if (!empty($user['product_image'])) { ?>
                                                    <img src="<?php echo PRODUCT_URL . 'product/' . $user['product_image']; ?>" alt="product image" width="50" height="50">
                                            </td>
                                            <?php } else {   ?>
                                                <td align="center">
                                                    <img src="<?php echo PRODUCT_URL . 'logo1.png'; ?>" alt="category image" width="50" height="50">
                                                </td>
                                            <?php } ?>
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

<script>
$( document ).ready(function() {
    $('#dataTable').DataTable();
});
</script>