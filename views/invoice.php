<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/OrderFunctions.php"); ?>
<?php

    $token = (isset($_GET['order_id']) ? intval($_GET['order_id']):0);

    $user_id = (isset($_GET['user_id']) ? intval($_GET['user_id']):0);





    if ($token > 0)

    {

       $invoice = OrderFunctions:: get_invoice($user_id,$token);

      

       if (empty($invoice)) 

       {

        header('location:orders.php');

        exit;

       }

    }

    else

    {

        header('location:orders.php');

        exit;

    }

?>

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
          <div class="row">

                <div class="col-md-12 mb-2">

                    <h2 style="text-align: center;">INVOICE<button class="noprint" style="float: right;" onclick="window.print();"><i class="fa fa-download" aria-hidden="true" style="color: darkred;font-size: 30px;"></i></button></h2>

                </div>

                <div class="col-md-6">

                    <img height="50" width="240" class="img-responsive p-2" src="<?php echo WEBSITE_BASE_URL; ?>images/logo.png" style="height: 100px;">

                </div>

                <div class="col-md-6">

                    <address>

                        <b>Addresh</b> :Jabalpur Madhya Pradesh
 
                        <br>
                        <b>PIN</b> – 482002 <br>

                        <b>Gstin no</b> :<br>

                        <b>Cin</b>: U18209MP2023PTC064429<br>

                        <b>Email</b>: care@gmail.com

                    </address>

                </div>

                <div class="col-md-12">

                    <?php // print_r($invoice) ?>

                    <div class="row">

                        <div class="col-md-6 card">

                            <div><b>Order Id</b> : <?php echo "TNS".str_pad($invoice['id'], 6, '0', STR_PAD_LEFT); ?></div> 

                            <div><b>Order Date</b> : <?php echo date('d M, Y',strtotime($invoice['order_datetime'])); ?></div> 

                        </div>

                        <div class="col-md-6 card">

                            <div><b>Invoice Date</b> : <span style="font-size: 11px;"><?php echo date('d M,Y h:i:s a',strtotime($invoice['order_datetime'])); ?></span></div> 

                            <div><b>Client Name</b> : <?php echo $_SESSION['username']; ?></div> 

                            <div><b>Client ID</b> : <?php //echo $profile['id']; ?></div> 

                            <div><b>Email </b> : <?php //echo $profile['email_id']; ?></div>

                        </div>

                        <div class="col-md-6 card">

                            Ship To

                        </div>

                        <div class="col-md-6 card">

                           <div><b><?php echo $invoice['customer_name'];?></b><br>

                        <?php echo $invoice['address'].", ".$invoice['city'].", ".$invoice['state'];?></div>

                            <div><b>Pincode:</b> <?php echo $invoice['pincode'];?></div>

                            <div><b>Mobile:</b> <?php echo $invoice['customer_mobile'];?></div>

                        </div>

                    </div>

                    <div class="row">

                        <table class="table table-bordered " cellpadding="20" cellspacing="0" style="padding: 10px 0px;text-align:right !important;">            

                            <tr class="heading text-center">

                                <th>

                                    ITEM

                                </th>

                                <th>

                                    BRAND

                                </th>

                                <th>

                                    SIZE

                                </th>

                                <th>

                                    COLOR

                                </th>  

                                <th>

                                    PRICE

                                </th>

                                <th>

                                    QTY

                                </th>               

                                <th>

                                    AMOUNT

                                </th>

                            </tr>

                            <?php
                              $subtotal=0;
                            foreach ($invoice['items'] as $key => $item) 

                            {

                              ?>

                                <tr class="item text-center">

                                    <td>

                                        <?php echo $item['product_name'];?>

                                    </td>

                                    <td>

                                        <?php echo "The Nirman Store";?>

                                    </td>

                                    <td>

                                       <?php //echo $item['product_size']; ?>

                                    </td>

                                    <td>

                                        -

                                    </td>

                                    <td>

                                        <span class="fa fa-inr">&nbsp;<?php echo number_format((float)$item['item_price'], 2, '.', '');?></span>

                                    </td>

                                    <td>

                                        <?php echo $item['quantity']." ".@$item['unit'];?>

                                    </td>

                                    <td>

                                        <span class="fa fa-inr">&nbsp;<?php  
                                        $total_price=$item['unit_price'];
                                        $subtotal+=$item['unit_price'];
                                        echo number_format((float)$total_price, 2, '.', ''); ?></span>

                                    </td>

                                </tr>

                              <?php

                            }

                            ?>              

                            <tr class="item last">

                                <td colspan="6" class="p-1"><b>Subtotal:</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php 
                                        $subtotal= number_format((float)$subtotal, 2, '.', '');

                                        echo number_format((float)$subtotal, 2, '.', '');
                                     ?>

                                </td>

                            </tr>              

                            <!-- <tr class="item last">

                                <td colspan="6" class="p-1"><b>MSD:</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php echo floatval($invoice['msd']); ?>

                                </td>

                            </tr>             

                            <tr class="item last">

                                <td colspan="6" class="p-1"><b>After MSD:</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php echo floatval($invoice['subtotal'] - $invoice['msd']); ?>

                                </td>

                            </tr> -->               

                            <tr class="item last">

                                <td colspan="6" class="p-1"><b><!-- IGST -->GST :</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php

                                    $igst= number_format((float)@$invoice['igst'], 2, '.', '');

                                     echo number_format((float)@$invoice['igst'], 2, '.', '');
                                     //echo floatval($invoice['igst']); ?>

                                </td>

                            </tr>              

                           <!--  <tr class="item last">

                                <td colspan="6" class="p-1"><b>CGST + SGST:</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span>0.00

                                </td>

                            </tr>    -->         

                            <tr class="item last">

                                <td colspan="6" class="p-1"><b>Shipping Charge:</b></td>                

                                <td class="text-dark p-1" style="font-size: 100%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php //echo floatval($invoice['delivery_charge']); 
                                    $delivery_charge= number_format((float)$invoice['delivery_charges'], 2, '.', '');

                                        echo number_format((float)$invoice['delivery_charges'], 2, '.', '');
                                    ?>

                                </td>

                            </tr>               

                            <tr class="total">

                                <td colspan="6"><b>Total Amount:</b></td>                

                                <td class="text-dark" style="font-size: 110%;">

                                    <span class="fa fa-inr">&nbsp;</span><?php //echo floatval($invoice['total_amount']); 
                                   // echo number_format((float)$invoice['total_amount'], 2, '.', '');

                                       $total_amount=$subtotal+$igst+$delivery_charge;
                                       echo number_format((float)$total_amount, 2, '.', '');

                                    ?>

                                </td>

                            </tr>            

                        </table>                        

                    </div>

                    <div class="row text-center mt-3">

                        <div class="col-md-12">

                            <a href="javascript:void(0);" class="text-center">The Nirman Store Private Limited</a>

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

                        data: {order_id:order_id, "c1e7c5cfd3d06ee8ef28b5c807d50f3b": "mark_product_delivered"},

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

                        data: {order_id:order_id, "c1e7c5cfd3d06ee8ef28b5c807d50f3b": "cancel_order"},

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