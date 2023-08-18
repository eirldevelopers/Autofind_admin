<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/typefunctions.php"); ?>
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
      <h2>Sellers</h2>
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
                           <th>Name</th>
                           <th>Email ID</th>
                           <th>Mobile</th>
                           <th style="width: 40px">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           $users = TypeFunctions::get_sellers();
                           
                           if (
                               mysqli_num_rows($users) >
                               0
                           ) {
                               $i = 1;
                               foreach ($users as $user) {
                                   //echo "<pre>";
                                 //print_r($user); die;;
                                ?>
                        <tr>
                           <td align="center"><?php echo $i++; ?></td>
                           <td><?php echo $user['seller_name']; ?></td>
                           <td><?php echo $user['email_id']; ?></td>
                           <td><?php echo $user['mobile_no']; ?></td>
                           <td class="noExport" align="center">
                              <div class="btn-group">
                                 <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                 <ul class="dropdown-menu">
                                   
                                    <li><a class="dropdown-item" href="menupermsn.php?3LVyQ9v4bZORQR5=<?php echo base64_encode(base64_encode("uid=" . $user['id'])); ?>&utype=2">Assign Permissions</a></li>
                                  
                                   
                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="change_user_status(<?php echo $user['id']; ?>);">Activate A/c</a></li>

                                 </ul>
                              </div>
                           </td>
                           <!-- <td class="noExport" align="center">
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
                              </td> -->
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
