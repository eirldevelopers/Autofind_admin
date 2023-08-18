<?php include_once(__DIR__ . "/../header.php"); ?>
<?php include_once(__DIR__ . "/../sidebar.php"); ?>
<?php include_once(__DIR__ . "/../topbar.php"); ?>
<?php include_once(__DIR__ ."/../models/videocallbookingfunctions.php"); ?>


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
        <h2>Video Call Booking</h2>
    </div>
    <!-- <div class="col-sm-4">
        <div class="title-action">
            <a href="javascript:void(0);" class="btn btn-primary" onclick="add_promocode();">Add New</a>
        </div>
    </div> -->
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
                                    <th>Order ID</th> 
                                    <th>Name</th>
                                    <th>Brand</th> 
                                    <th>Car Model</th>
                                    <th>Address</th> 
                                    <th>Number</th> 
                                    <th>Car Problem</th> 
                                    <th>Slot No</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Amount</th>
                                    
                                    <th style="width:40px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = VideoCallBookingFunctions::get_video_call_booking();
                                $opu_res = VideoCallBookingFunctions::get_opration_user();
                                
                            if (mysqli_num_rows($res)>0) {
                                    $i = 1;
                                    foreach ($res as $value) { 
                                       // print_r($user); die;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i++; ?></td>
                                            <th align="center"><?php echo $value['order_id']; ?>
                                            <th align="center"><?php echo $value['name']; ?>
                                            <th align="center"><?php echo $value['brand']; ?>
                                            <th align="center"><?php echo $value['car_model']; ?>
                                            <th align="center"><?php echo $value['address']; ?>
                                            <th align="center"><?php echo $value['number']; ?>
                                            <th align="center"><?php echo $value['car_problem']; ?>
                                            <th align="center"><?php echo $value['slot_no']; ?>
                                            <th align="center"><?php echo $value['start_time']; ?>
                                            <th align="center"><?php echo $value['end_time']; ?>
                                            <th align="center"><?php echo $value['amount']; ?>

                                           <!--  <td align="center">
                                                <?php //echo $user['imgtyp'] ?>
                                            </td> -->
                                            <td align="center">
                                                <select class="from-control" onchange="assign_booking('<?php echo $value['order_id']; ?>')">
                                                    <option selected="">--Assign Booking--</option>
                                                    <?php
                                                     if (mysqli_num_rows($opu_res)>0) {
                                                       foreach ($opu_res as $val) { 
                                                    ?>
                                                   <option value="<?php echo $val['id']; ?>"><?php echo $val['user_name']; ?></option>
                                                <?php }} ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="13">Click On Add New Button</td>
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
<script type="text/javascript">
    function assign_booking()
    {

    }
</script>