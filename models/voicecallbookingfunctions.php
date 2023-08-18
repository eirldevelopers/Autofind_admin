<?php
    include_once("generalfunctions.php");
    
    class VoiceCallBookingFunctions
    {
       static function get_voice_call_booking() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT * FROM `user_plans` JOIN `user_details` ON `user_plans`.`order_id`=`user_details`.`order_id` LEFT JOIN `voice_slot` ON `voice_slot`.`id`=`user_details`.`slot` WHERE `user_details`.`call_type`='voice_call' order by `user_plans`.`id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        
        static function get_opration_user() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT * FROM `admin_users` WHERE role='2' AND active='1' order by `admin_users`.`user_name` ASC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }       

        static function change_promocode_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_promocodes` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function edit_promocode() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `ecom_promocodes` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }

       
        static function create_promocode() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            //print_r($_POST); die;
           $promocode =$_POST['promocode'];
           $promocode_title =$_POST['promocode_title'];
           $promocode_description =$_POST['promocode_description'];
           $promocode_type =$_POST['promocode_type'];
           $number_of_time_usage =$_POST['number_of_time_usage'];
           $per_user =$_POST['per_user'];
           $discount =$_POST['discount'];
           $expiry_date =$_POST['expiry_date'];
           if($_SESSION['role']=='3')
           {
             $seller_id =$_SESSION['user_id'];
           }else{
             $seller_id ='';
           }
            $result = mysqli_query($con, "INSERT INTO `ecom_promocodes`(`promocode`,`promocode_title`,`promocode_description`,`number_of_time_usage`,`per_user`,`promocode_type`,`discount`,`expiry_date`,`seller_id`) VALUES ('$promocode','$promocode_title','$promocode_description','$number_of_time_usage','$per_user','$promocode_type','$discount','$expiry_date','$seller_id')");
                if($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Promocode create successfully."
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

        static function update_promocode() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $promocode_one_id = $_POST['promocode_one_id'];
            $promocode =$_POST['edit_promocode'];
            $promocode_title =$_POST['edit_promocode_title'];
            $promocode_description =$_POST['edit_promocode_description'];
            $promocode_type =$_POST['edit_promocode_type'];
            $number_of_time_usage =$_POST['edit_number_of_time_usage'];
            $per_user =$_POST['edit_per_user'];
            $discount =$_POST['edit_discount'];
            $expiry_date =$_POST['edit_expiry_date'];
            $result = mysqli_query($con, "UPDATE `ecom_promocodes` SET  `promocode`='$promocode',`promocode_title`='$promocode_title',`promocode_description`='$promocode_description',`promocode_type`='$promocode_type',`number_of_time_usage`='$number_of_time_usage',`per_user`='$per_user',`discount`='$discount',`expiry_date`='$expiry_date' WHERE `id`='$promocode_one_id'");
            
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Promocode update successfully."
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

        static function delete_promocode() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
                $result = mysqli_query($con, "UPDATE `ecom_promocodes` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Promocode delete successfully."
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
 }