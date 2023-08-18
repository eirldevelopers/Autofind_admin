<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/taxsettingfunctions.php"); ?>

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
                <h4 class="modal-title">Add Tax Setting</h4>
            </div>
            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_tax_setting();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="hsn_code" name="hsn_code" placeholder="hsn code" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                      
                        <input type="text" class="form-control" id="rate" name="rate" placeholder="rate" autocomplete="off" required />
                    </div>
                   
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("add_tax_setting"); ?>">
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
                <h4 class="modal-title">Update Tax Setting</h4>
            </div>
            <form role="form" id="editForm" method="post" onsubmit="event.preventDefault(); update_tax_rate();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="edit_hsn_code" name="edit_hsn_code" placeholder="hsn code" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="dc_one_id" name="dc_one_id" autocomplete="off" />
                        <input type="text" class="form-control" id="edit_rate" name="edit_rate" placeholder="rate" autocomplete="off" required />
                    </div>
                   
                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("update_tax_rate"); ?>">
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
        <h2>Tax Setting</h2>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_tax_setting();">Add New</a>
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
                                    <th>HSN Code</th>
                                    <th>Rate Percentage</th>

                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = TaxsettingFunctions::get_tax_data();

                                if(mysqli_num_rows($res) >0) {
                                    $i = 1;
                                    foreach ($res as $value) { ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td><?php echo $value['hsn_code']; ?></td>
                                            <td><?php echo $value['rate']; ?></td>

                                        
                                            <td class="noExport" align="center">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                    <ul class="dropdown-menu" style="width: auto;">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="edit_tax_setting(<?php echo $value['id']; ?>);">Edit</a>
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