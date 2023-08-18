<?php
    include_once("generalfunctions.php");
    
    class DeliveryChargesFunctions
    {

         static function get_delivery_charges() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_delivery_charges`  order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

       
        static function update_delivery_charges() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $dc_one_id = $_POST['dc_one_id'];
            $amount =$_POST['amount'];
            $result = mysqli_query($con, "UPDATE `ecom_delivery_charges` SET  `amount`='$amount' WHERE `id`='$dc_one_id'");
            
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Delivery charges update successfully."
                        ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Something went wrong."
                    ]);
                }
            
            mysqli_close($con);
            return $data;
        }

   static function edit_delivery_charges() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `ecom_delivery_charges` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }
      
 }