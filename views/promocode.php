<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/promocodeFunctions.php"); ?>

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
                <h4 class="modal-title">Add Promocode</h4>
            </div>
            <form role="form" id="myForm" method="post" onsubmit="event.preventDefault(); create_promocode();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                         <input type="text" class="form-control" id="promocode" name="promocode" placeholder="Promocode" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="promocode_title" name="promocode_title" placeholder="Promocode Title" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="promocode" name="promocode_description" placeholder="Promocode Description" autocomplete="off" required />
                    </div>
                    
                     <div class="form-group">
                         <input type="number" class="form-control" id="number_of_time_usage" name="number_of_time_usage" placeholder="Number Of Time Usage" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="per_user" name="per_user" placeholder="Per User" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <select class="form-control" name="promocode_type"> 
                           <option value="1">Percentage</option>
                           <option value="2">Flat</option>
                         </select>
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="discount" name="discount" placeholder="discount" autocomplete="off" required />
                    </div>

                     <div class="form-group">
                         <input type="date" class="form-control" id="expiry_date" name="expiry_date" placeholder="Promocode" autocomplete="off" required />
                    </div>

                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("create_promocode"); ?>">
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
                <h4 class="modal-title">Update Promocode</h4>
            </div>
            <form role="form" id="editForm" method="post" onsubmit="event.preventDefault(); update_promocode();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="promocode_one_id" name="promocode_one_id" autocomplete="off" />
                      <!--   <input type="text" class="form-control" id="l_one_category" name="l_one_category" placeholder="Category Name" autocomplete="off" required /> -->
                    </div>
                    <div class="form-group">
                         <input type="text" class="form-control" id="edit_promocode" name="edit_promocode" placeholder="Promocode" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="edit_promocode_title" name="edit_promocode_title" placeholder="Promocode Title" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="edit_promocode_description" name="edit_promocode_description" placeholder="Promocode Description" autocomplete="off" required />
                    </div>
                    
                     <div class="form-group">
                         <input type="number" class="form-control" id="edit_number_of_time_usage" name="edit_number_of_time_usage" placeholder="Number Of Time Usage" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="edit_per_user" name="edit_per_user" placeholder="Per User" autocomplete="off" required />
                    </div>
                     <div class="form-group">
                         <select class="form-control" id="edit_promocode_type" name="edit_promocode_type"> 
                           <option value="1">Percentage</option>
                           <option value="2">Flat</option>
                         </select>
                    </div>
                     <div class="form-group">
                         <input type="text" class="form-control" id="edit_discount" name="edit_discount" placeholder="edit_discount" autocomplete="off" required />
                    </div>

                     <div class="form-group">
                         <input type="date" class="form-control" id="edit_expiry_date" name="edit_expiry_date" placeholder="Promocode" autocomplete="off" required />
                    </div>

                    <input type="hidden" name="c1e7c5cfd3d06ee8ef28b5c807d50f3b" value="<?php echo base64_encode("update_promocode"); ?>">
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
        <h2>Promocode</h2>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_promocode();">Add New</a>
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
                                   
                                    <th>Promocode</th>
                                    <th>Promocode Title</th> 
                                    <th>Promocode Description</th>
                                    <th>Number Of Time Usage </th> 
                                    <th>Per User</th> 
                                    <th>Promocode Type</th> 
                                    <th>Discount</th>
                                    <th>Expiry Date</th> 
                                    <th>Is Expired</th> 
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = PromocodeFunctions::get_promocode();
                                
                            if (mysqli_num_rows($users)>0) {
                                    $i = 1;
                                    foreach ($users as $user) { 
                                       // print_r($user); die;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <th align="center"><?php echo $user['promocode']; ?>
                                            <th align="center"><?php echo $user['promocode_title']; ?>
                                            <th align="center"><?php echo $user['promocode_description']; ?>
                                            <th align="center"><?php echo $user['number_of_time_usage']; ?>
                                            <th align="center"><?php echo $user['per_user']; ?>
                                            <th align="center"><?php if($user['promocode_type']==1){echo 'Percentage'; }else{echo "Flat"; } ?>
                                            <th align="center"><?php echo $user['discount']; ?>
                                            <th align="center"><?php echo $user['expiry_date']; ?>
                                            <th align="center"><?php if($user['is_expired']==1){echo "Expired"; } ?>
                                           <!--  <td align="center">
                                                <?php //echo $user['imgtyp'] ?>
                                            </td> -->
                                            <td class="noExport" align="center">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                    <ul class="dropdown-menu" style="width: auto;">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="edit_promocode(<?php echo $user['id']; ?>);">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="delete_promocode(<?php echo $user['id']; ?>);">Delete</a>
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
