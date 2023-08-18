<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/OrderFunctions.php"); ?>


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

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="table-responsive" style="overflow-x: unset;">
                        <table class="table table-striped table-bordered table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                   
                                    <th>Order No</th>
                                    <th>User Name</th> 
                                    <th>Email Id</th>
                                    <th>Contact Number</th> 
                                    <th>Payment Method</th> 
                                    <th>Order Status</th> 
                                    <th>Payment Status</th>
                                    <th>Order Amount</th> 
                                    <th>Delivery Charges</th> 
                                    <th style="width: 40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = OrderFunctions::get_order();
                                
                            if (mysqli_num_rows($orders)>0) {
                                    $i = 1;
                                    foreach ($orders as $order) { 
                                        //echo "<pre>";
                                       // print_r($order); die;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <td align="center"><?php echo $order['order_no']; ?>
                                            <td align="center"><?php echo $order['first_name']; ?>
                                            <td align="center"><?php echo $order['email']; ?>
                                            <td align="center"><?php echo $order['phone']; ?>
                                            <td align="center"><?php echo $order['payment_method']; ?>
                                            <td align="center"><?php if($order['order_track_status']==1){echo 'Proccessing'; }else if($order['order_track_status']==2){echo 'Delivered'; }else{echo "Canceled"; } ?>
                        <td align="center"><?php if($order['payment_status']==1){echo 'Successful'; }else if($order['payment_status']==2){echo 'Failed'; }else{ echo "Pending"; } ?>
                                            <td align="center"><?php echo $order['total_order_amt']; ?></td>
                                            <td align="center"><?php echo $order['delivery_charges']; ?></td>
                                            
                                            <td class="noExport" align="center">
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle"></button>
                                                    <ul class="dropdown-menu" style="width: auto;">
                                                     <?php if($order['order_track_status']==1){?>
                                                         <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="mark_product_delivered(<?php echo $order['order_id']; ?>);">Deliverd</a>
                                                        </li>
                                                        <?php } ?> 
                                                        <?php if($order['order_track_status']==1){?>
                                                         <li>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="cancel_order(<?php echo $order['order_id']; ?>);">Cancel</a>
                                                        </li> 
                                                    <?php } ?>
                                                          <li>
                                                            <a class="dropdown-item" href="invoice.php?order_id=<?php echo $order['order_id']."&user_id=".$order['user_id'];?>" >View Invoice</a>
                                                        </li> 
                                                     <!--    <li>
                                                            <a class="dropdown-item" href="ordered_products.php?token=<?php echo $order['order_id'];?>">Order Details</a>
                                                        </li> -->
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



            function mark_product_delivered(order_id){

                if (confirm('Are you sure want to order delivered ?'))

                 {

                    $('#overlay').show();

                    $.ajax({

                        url: '../controllers/order.php',

                        method: 'post',
                    data: {order_id:order_id,"c1e7c5cfd3d06ee8ef28b5c807d50f3b":  btoa("mark_product_delivered")},

                        success: function(data){

                            var response = $.parseJSON(data);

                              if(response.success == 1)

                              {

                                  alert(response.msg);

                                  window.location.reload();

                              }

                              else

                              {

                                  alert(response.msg);

                                  $("#overlay").hide();

                              }

                        }

                    });



                 }

            }



            function cancel_order(order_id){

                if (confirm('Are you sure?'))

                 {

                    $('#overlay').show();

                    $.ajax({

                        url: '../controllers/order.php',

                        method: 'post',

                        data: {order_id:order_id, "c1e7c5cfd3d06ee8ef28b5c807d50f3b":btoa("cancel_order")},

                        success: function(data){

                            var response = $.parseJSON(data);

                              if(response.success == 1)

                              {

                                  alert(response.msg);

                                  window.location.reload();

                              }

                              else

                              {

                                  alert(response.msg);

                                  $("#overlay").hide();

                              }

                        }

                    });



                 }

            }

                            

        </script>