<?php
    include_once("generalfunctions.php");
    
    class CategoryFunctions
    {
        static function get_category1($uid=0) {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `is_deleted`='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        static function get_all_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `is_deleted`='0' order by `l1_category`")or die(mysqli_error($con));
            $row = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return $row;
        }

        static function change_category1_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_level1_category` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function edit_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }

       
        static function create_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $l1_category = $_POST['l1_category'];
            $query = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `l1_category` = '$l1_category' AND `is_deleted` = '0' LIMIT 1")or die(mysqli_error($con));
            if (mysqli_num_rows($query) > 0) {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Duplicate Category."
                ]);
            } else {
                if($_FILES["image_file"]["name"]) {
                    $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                    move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                    $result = mysqli_query($con, "INSERT INTO `ecom_level1_category`(`l1_category`, `image_file`, `active`) VALUES ('$l1_category', '$image_file', '1')")or die(mysqli_error($con));
                } else {
                    $result = mysqli_query($con, "INSERT INTO `ecom_level1_category`(`l1_category`, `active`) VALUES ('$l1_category', '1')")or die(mysqli_error($con));;
                }
                if($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Category create successfully."
                        ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Something went wrong."
                    ]);
                }
            }
            mysqli_close($con);
            return $data;
        }

        static function update_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_id = $_POST['category_one_id'];
            $l1_category = $_POST['l_one_category'];
            $query = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `l1_category` = '$l1_category' AND `id` <> '$category_id' AND `is_deleted` = '0' LIMIT 1")or die(mysqli_error($con));
            if (mysqli_num_rows($query) > 0) {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Duplicate Category."
                ]);
            } else {
                if($_FILES["image_file_one"]["name"]) {
                    $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file_one"]["name"])[1];
                    move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                    $result = mysqli_query($con, "UPDATE `ecom_level1_category` SET `l1_category`= '$l1_category', `image_file`='$image_file' WHERE `id`='$category_id'")or die(mysqli_error($con));
                } else {
                    $result = mysqli_query($con, "UPDATE `ecom_level1_category` SET `l1_category`= '$l1_category' WHERE `id`='$category_id'")or die(mysqli_error($con));;
                }
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Category update successfully."
                        ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Something went wrong."
                    ]);
                }
            }
            mysqli_close($con);
            return $data;
        }

        static function delete_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $query = mysqli_query($con, "SELECT * FROM `ecom_level2_category` WHERE `l1c_id` = '$id' AND `is_deleted` = '0' LIMIT 1");
            if (mysqli_num_rows($query) > 0) {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Firstly delete all level two of this category."
                ]);
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level1_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Category delete successfully."
                        ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Something went wrong."
                    ]);
                }
            }
            mysqli_close($con);
            return $data;
        }

        static function get_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT c2.*, c1.id as cid, c1.l1_category FROM ecom_level2_category c2 INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        static function create_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $l2_category = $_POST['l2_category'];
            $l1c_id = $_POST['l1c_id'];
            if($_FILES["image_file"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "INSERT INTO `ecom_level2_category`(`l2_category`, `l1c_id`, `image_file`, `active`) VALUES ('$l2_category', '$l1c_id', '$image_file', '1')")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "INSERT INTO `ecom_level2_category`(`l2_category`, `l1c_id`, `active`) VALUES ('$l2_category', '$l1c_id', '1')")or die(mysqli_error($con));;
            }
            if($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category2 create successfully."
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

        static function edit_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level2_category` WHERE `id`='$id'");
            $find = mysqli_fetch_array($result);
            $row = [];
            $cat_result = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `is_deleted`='0' order by `l1_category`")or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($cat_result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return [$row, $find];
        }

        static function update_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_two_id = $_POST['category_two_id'];
            $l2_category = $_POST['l_two_category'];
            $l1c_id = $_POST['l1c_id'];
            if($_FILES["image_file_one"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file_one"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "UPDATE `ecom_level2_category` SET `l2_category`= '$l2_category', `l1c_id`= '$l1c_id', `image_file`='$image_file' WHERE `id`='$category_two_id'")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level2_category` SET `l2_category`= '$l2_category', `l1c_id`= '$l1c_id' WHERE `id`='$category_two_id'")or die(mysqli_error($con));;
            }
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category2 update successfully."
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

        static function change_category2_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_level2_category` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function delete_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $query = mysqli_query($con, "SELECT * FROM `ecom_products` WHERE `l2c_id` = '$id' AND `is_deleted` = '0' LIMIT 1");
            if (mysqli_num_rows($query) > 0) {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Firstly delete all product of this category."
                ]);
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level2_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"Category2 delete successfully."
                        ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Something went wrong."
                    ]);
                }
            }
            mysqli_close($con);
            return $data;
        }

        static function get_all_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT c2.*, c1.id as cid, c1.l1_category FROM ecom_level2_category c2 INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c2.is_deleted='0' AND c1.is_deleted='0' order by c2.id DESC")or die(mysqli_error($con));
            $row = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return $row;
        }

        static function get_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT c3.*, c2.id as cid, c2.l2_category FROM ecom_level3_category c3 INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        static function create_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $l3_category = $_POST['l3_category'];
            $l2c_id = $_POST['l2c_id'];
            if($_FILES["image_file"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "INSERT INTO `ecom_level3_category`(`l3_category`, `l2c_id`, `image_file`, `active`) VALUES ('$l3_category', '$l2c_id', '$image_file', '1')")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "INSERT INTO `ecom_level3_category`(`l3_category`, `l2c_id`, `active`) VALUES ('$l3_category', '$l2c_id', '1')")or die(mysqli_error($con));;
            }
            if($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category3 create successfully."
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

        static function edit_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level3_category` WHERE `id`='$id'");
            $find = mysqli_fetch_array($result);
            $row = [];
            $cat_result = mysqli_query($con, "SELECT c2.*, c1.id as cid, c1.l1_category FROM ecom_level2_category c2 INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($cat_result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return [$row, $find];
        }

        static function update_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_three_id = $_POST['category_three_id'];
            $l3_category = $_POST['l_three_category'];
            $l2c_id = $_POST['l2c_id'];
            if($_FILES["image_file_one"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file_one"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "UPDATE `ecom_level3_category` SET `l3_category`= '$l3_category', `l2c_id`= '$l2c_id', `image_file`='$image_file' WHERE `id`='$category_three_id'")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level3_category` SET `l3_category`= '$l3_category', `l2c_id`= '$l2c_id' WHERE `id`='$category_three_id'")or die(mysqli_error($con));;
            }
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category3 update successfully."
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

        static function change_category3_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_level3_category` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function delete_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "UPDATE `ecom_level3_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category3 delete successfully."
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

        static function get_all_category3() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT c3.*, c2.id as cid, c2.l2_category FROM ecom_level3_category c3 INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by c3.id DESC")or die(mysqli_error($con));
            $row = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return $row;
        }

        static function get_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT c4.*, c3.id as cid, c3.l3_category FROM ecom_level4_category c4 INNER JOIN ecom_level3_category c3 on c4.l3c_id = c3.id INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c4.is_deleted='0' AND c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        static function create_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $l4_category = $_POST['l4_category'];
            $l3c_id = $_POST['l3c_id'];
            if($_FILES["image_file"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "INSERT INTO `ecom_level4_category`(`l4_category`, `l3c_id`, `image_file`, `active`) VALUES ('$l4_category', '$l3c_id', '$image_file', '1')")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "INSERT INTO `ecom_level4_category`(`l4_category`, `l3c_id`, `active`) VALUES ('$l4_category', '$l3c_id', '1')")or die(mysqli_error($con));;
            }
            if($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category4 create successfully."
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

        static function edit_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level4_category` WHERE `id`='$id'");
            $find = mysqli_fetch_array($result);
            $row = [];
            $cat_result = mysqli_query($con, "SELECT c3.*, c2.id as cid, c2.l2_category FROM ecom_level3_category c3 INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by c3.id DESC")or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($cat_result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return [$row, $find];
        }

        static function update_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_four_id = $_POST['category_four_id'];
            $l4_category = $_POST['l_four_category'];
            $l3c_id = $_POST['l3c_id'];
            if($_FILES["image_file_one"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file_one"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "UPDATE `ecom_level4_category` SET `l4_category`= '$l4_category', `l3c_id`= '$l3c_id', `image_file`='$image_file' WHERE `id`='$category_four_id'")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level4_category` SET `l4_category`= '$l4_category', `l3c_id`= '$l3c_id' WHERE `id`='$category_four_id'")or die(mysqli_error($con));;
            }
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category4 update successfully."
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

        static function change_category4_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_level4_category` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function delete_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "UPDATE `ecom_level4_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category4 delete successfully."
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

        static function get_all_category4() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT c4.*, c3.id as cid, c3.l3_category FROM ecom_level4_category c4 INNER JOIN ecom_level3_category c3 on c4.l3c_id = c3.id INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c4.is_deleted='0' AND c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            $row = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return $row;
        }

        static function get_category5() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT c5.*, c4.id as cid, c4.l4_category FROM ecom_level5_category c5 INNER JOIN ecom_level4_category c4 on c5.l4c_id = c4.id INNER JOIN ecom_level3_category c3 on c4.l3c_id = c3.id INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c4.is_deleted='0' AND c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

        static function create_category5() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $l5_category = $_POST['l5_category'];
            $l4c_id = $_POST['l4c_id'];
            if($_FILES["image_file"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "INSERT INTO `ecom_level5_category`(`l5_category`, `l4c_id`, `image_file`, `active`) VALUES ('$l5_category', '$l4c_id', '$image_file', '1')")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "INSERT INTO `ecom_level5_category`(`l5_category`, `l4c_id`, `active`) VALUES ('$l5_category', '$l4c_id', '1')")or die(mysqli_error($con));;
            }
            if($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category5 create successfully."
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

        static function edit_category5() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level5_category` WHERE `id`='$id'");
            $find = mysqli_fetch_array($result);
            $row = [];
            $cat_result = mysqli_query($con, "SELECT c4.*, c3.id as cid, c3.l3_category FROM ecom_level4_category c4 INNER JOIN ecom_level3_category c3 on c4.l3c_id = c3.id INNER JOIN ecom_level2_category c2 on c3.l2c_id = c2.id INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c4.is_deleted='0' AND c3.is_deleted='0' AND c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($cat_result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return [$row, $find];
        }

        static function update_category5() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_five_id = $_POST['category_five_id'];
            $l5_category = $_POST['l_five_category'];
            $l4c_id = $_POST['l4c_id'];
            if($_FILES["image_file_one"]["name"]) {
                $image_file = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["image_file_one"]["name"])[1];
                move_uploaded_file($_FILES['image_file']['tmp_name'], "../assets/img/category/". $image_file);
                $result = mysqli_query($con, "UPDATE `ecom_level5_category` SET `l5_category`= '$l5_category', `l4c_id`= '$l4c_id', `image_file`='$image_file' WHERE `id`='$category_five_id'")or die(mysqli_error($con));
            } else {
                $result = mysqli_query($con, "UPDATE `ecom_level5_category` SET `l5_category`= '$l5_category', `l4c_id`= '$l4c_id' WHERE `id`='$category_five_id'")or die(mysqli_error($con));;
            }
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category5 update successfully."
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

        static function change_category5_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_level5_category` SET `active`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function delete_category5() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "UPDATE `ecom_level5_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Category5 delete successfully."
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
