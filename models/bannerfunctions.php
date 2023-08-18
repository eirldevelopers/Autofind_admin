<?php
    include_once("generalfunctions.php");
    
    class BannerFunctions
    {
       static function get_banner() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_bannerimages` WHERE is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

       

        static function change_banner_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_bannerimages` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function edit_banner() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `ecom_bannerimages` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }

       
        static function create_banner() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
                if($_FILES["image_file"]["name"]) {
                    $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                    move_uploaded_file($_FILES['image_file']['tmp_name'],TARGET_DIR."images/slides/". $image_file);
                    $result = mysqli_query($con, "INSERT INTO `ecom_bannerimages`(`bannerimg`) VALUES ('$image_file')");
                } 
                if($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Banner create successfully."
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

        static function update_banner() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $banner_one_id = $_POST['banner_one_id'];
           
                if($_FILES["edit_bannerimg"]["name"]) {
                    $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["edit_bannerimg"]["name"])[1];
                    move_uploaded_file($_FILES['image_file']['tmp_name'], TARGET_DIR."images/slides/". $image_file);
                    $result = mysqli_query($con, "UPDATE `ecom_bannerimages` SET  `bannerimg`='$image_file' WHERE `id`='$banner_one_id'");
                } 
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Banner update successfully."
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

        static function delete_banner() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
                $result = mysqli_query($con, "UPDATE `ecom_bannerimages` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Banner delete successfully."
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