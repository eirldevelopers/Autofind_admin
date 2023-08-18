<?php
    include_once("generalfunctions.php");
    
    class Taxsettingfunctions
    {

         static function get_tax_data() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `tax_setting`  order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

       
        static function update_taxsetting() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            //print_r($_POST); die;
            $dc_one_id = $_POST['dc_one_id'];
            $rate =$_POST['edit_rate'];
            $hsn_code =$_POST['edit_hsn_code'];
           //echo "UPDATE `tax_setting` SET  `rate`='$rate' AND `hsn_code`='$hsn_code' WHERE `id`='$dc_one_id'"; die;
            $result = mysqli_query($con, "UPDATE `tax_setting` SET `hsn_code`=$hsn_code,`rate`=$rate WHERE `id`=$dc_one_id");
            
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Tax data update successfully."
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

   static function edit_taxsetting() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `tax_setting` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }

    static function create_promocode() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            //print_r($_POST); die;
           $rate =$_POST['rate'];
           $hsn_code =$_POST['hsn_code'];
            $result = mysqli_query($con, "INSERT INTO `tax_setting`(`rate`,`hsn_code`) VALUES ('$rate','$hsn_code')");
                if($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Tax add create successfully."
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