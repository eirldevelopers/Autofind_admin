<?php
    include_once("generalfunctions.php");
    
    class ProductFunctions {

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

        static function update_category1() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $category_id = $_POST['category_one_id'];
            $l1_category = $_POST['l_one_category'];
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
            mysqli_close($con);
            return $data;
        }

        static function get_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT c2.*, c1.id as cid, c1.l1_category FROM ecom_level2_category c2 INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c2.is_deleted='0' AND c1.is_deleted='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
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
            mysqli_close($con);
            return $data;
        }

        static function load_categories_l2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $l1c_id = $_POST['l1c_id'];
            $result = mysqli_query($con, "SELECT c2.*, c1.id as cid, c1.l1_category FROM ecom_level2_category c2 INNER JOIN ecom_level1_category c1 on c2.l1c_id = c1.id  WHERE c2.`l1c_id` = '$l1c_id' AND c2.`is_deleted` = '0'order by c2.id DESC")or die(mysqli_error($con));
            $data = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $data[] = $fetch;
            }
            mysqli_close($con);
            return $data;
        }

        
        static function get_all_category2() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `ecom_level2_category` WHERE is_deleted = '0' AND l1c_id = '1'")or die(mysqli_error($con));
            $row = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $row[] = $fetch;
            }
            mysqli_close($con);
            return $row;
        }

        static function get_product_unit() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = mysqli_query($con, "SELECT * FROM `ecom_product_units` ORDER BY `unit` ASC")or die(mysqli_error($con));
            $data = [];
            while($fetch = mysqli_fetch_assoc($result)) {
                $data[] = $fetch;
            }
            mysqli_close($con);
            return $data;
        }
        static function load_unit_value() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $unit_id = $_POST['unit_id'];
            $result = mysqli_query($con, "SELECT ev.*, eu.unit FROM `ecom_product_unit_value` ev INNER JOIN ecom_product_units eu ON ev.product_unit_id = eu.id WHERE `product_unit_id` = '$unit_id' ORDER BY `value` ASC")or die(mysqli_error($con));
            $data = '';
            while($fetch = mysqli_fetch_assoc($result)) {
                $data .= '<label class="col-sm-2">
                            <input id="genMale" name="unit_value_id[]" type="checkbox" value="'.$fetch['id'].'"> '.$fetch['value']. ' '. $fetch['unit'].'
                        </label>';
            }
            mysqli_close($con);
            return $data;
        }
        
        static function get_all_products() {
             $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());

             $seller_id=$_SESSION['user_id'];
            if($_SESSION['role']=='3')
            {
             $products = mysqli_query($con, "SELECT id FROM `ecom_products` WHERE seller_id=$seller_id ORDER BY id");
            if(mysqli_num_rows($products) > 0) 
              {
                $products_arr = [];
                 while($fetch = mysqli_fetch_assoc($products)) {
                    $products_arr[] = $fetch['id'];
                 } 
                    $all_pid=implode(',',$products_arr);
               }     
               
               $query = mysqli_query($con, "SELECT p.*, c1.id AS cid, c1.l1_category FROM ecom_products p INNER JOIN ecom_level1_category c1 ON p.l1c_id = c1.id   WHERE p.is_deleted = '0' AND p.id IN($all_pid) ORDER BY `p`.`id` DESC");
            }else{
              $query = mysqli_query($con, "SELECT p.*, c1.id AS cid, c1.l1_category FROM ecom_products p INNER JOIN ecom_level1_category c1 ON p.l1c_id = c1.id   WHERE p.is_deleted = '0' ORDER BY `id` DESC")or die(mysqli_error($con));    
            } 

            
            $result = [];
            $i = 0;
            while($fetch = mysqli_fetch_assoc($query)) {
              
                $result[] = $fetch;
                  $pimage = mysqli_query($con, "SELECT  search_image,catalog_image,cart_image FROM `ecom_product_images` WHERE pid='".$fetch['id']."'");
            if(mysqli_num_rows($pimage) > 0) 
              {
                 $fetch_img = mysqli_fetch_assoc($pimage);
                 //print_r($fetch_img); die;
                 $result[$i]['product_image'] = @$fetch_img['search_image'];
               }else{
                 $result[$i]['product_image'] ='';

               } 
                $result[$i]['unit_name'] = @$fetch['unit'].' Gram';
                $i++;
            }
            mysqli_close($con);
            return $result;
        }
        static function edit_product() {
            $id = $_POST['id'];
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT p.*,c1.id AS cid, c1.l1_category FROM ecom_products p INNER JOIN ecom_level1_category c1 ON p.l1c_id = c1.id   WHERE `p`.`id` = '$id' LIMIT 1")or die(mysqli_error($con));

            $fetch = mysqli_fetch_assoc($query);
            // $fetch['units'] = $data2; 
            // $fetch['unit_value'] = $data3;
            if ($fetch) {
                $data = json_encode([
                    "status"=>'1',
                    "message"=>"Product get successfully.",
                    "data" => $fetch,
                ]);
            } else {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Unable to get data."
                ]);
            }
            mysqli_close($con);
            return $data;
        }

        static function create_product() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $l1c_id = $_POST['l1c_id'];
            $l2c_id = $_POST['l2c_id'];
            $product_name = $_POST['product_name'];
            $description = $_POST['description'];
            $unit = $_POST['unit'];
            // $unit_id = $_POST['unit_id'];
            // $unit_value = $_POST['unit_value_id'];
            // $unit_value_id = implode(',', $unit_value);
            if($_FILES["product_image"]["name"]) {
                $product_image = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["product_image"]["name"])[1];
                move_uploaded_file($_FILES['product_image']['tmp_name'], "../../uploads/products/". $product_image);
                $query = mysqli_query($con, "INSERT INTO `ecom_products` (`l1c_id`, `l2c_id`, `product_name`, `product_image`, `description`, `unit`) VALUES ('$l1c_id', '$l2c_id', '$product_name', '$product_image', '$description', '$unit')")or die(mysqli_error($con));
                if ($query) {
                    $data = json_encode([
                        "status"=>'1',
                        "message"=>"Product create successfully."
                    ]);
                } else {
                    $data = json_encode([
                        "status"=>'0',
                        "message"=>"Unable to Add."
                    ]);
                }
            } else {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"File not found."
                ]);
            }
            mysqli_close($con);
            return $data;
        }

        static function update_product() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $product_id = $_POST['product_id'];
            $l1c_id = $_POST['l1c_id'];
            $l2c_id = $_POST['l2c_id'];
            $product_name = $_POST['product_name'];
            $description = $_POST['description'];
            $unit = $_POST['unit'];
            // $unit_id = $_POST['unit_id'];
            // $unit_value = $_POST['unit_value_id'];
            // $unit_value_id = implode(',', $unit_value);
            $data = "";
            $query = "";

        if($_FILES["product_image"]["name"]) {
                $product_image = 'image'.rand(00000, 99999). '.' .explode(".", $_FILES["product_image"]["name"])[1];
                $pro_query = mysqli_query($con, "SELECT * FROM `ecom_products` WHERE `id` = '$product_id'");
                $pro_fetch = mysqli_fetch_assoc($pro_query);
               // print_r($pro_fetch); die;
                if($pro_fetch['product_image']) {
                    if(file_exists("../../uploads/products/".$pro_fetch['product_image'])) {
                        unlink("../../uploads/products/".$pro_fetch['product_image']);
                    }
                }
                move_uploaded_file($_FILES['product_image']['tmp_name'], "../../uploads/products/". $product_image);
                $query = mysqli_query($con, "UPDATE `ecom_products` SET `l1c_id`='$l1c_id', `l2c_id`='$l2c_id', `product_name`='$product_name', `product_image`='$product_image', `short_description`='$description', `unit_id`='$unit' WHERE `id` = '$product_id'")or die(mysqli_error($con));
            } else {
                $query = mysqli_query($con, "UPDATE `ecom_products` SET `l1c_id`='$l1c_id', `l2c_id`='$l2c_id', `product_name`='$product_name', `short_description`='$description', `unit_id`='$unit' WHERE `id` = '$product_id'")or die(mysqli_error($con));
            }
            if ($query) {
                $data = json_encode([
                    "status"=>'1',
                    "message"=>"Product update successfully."
                ]);
            } else {
                $data = json_encode([
                    "status"=>'0',
                    "message"=>"Unable to Add."
                ]);
            }
            mysqli_close($con);
            return $data;
        }

        
        static function change_product_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['id'];
            $result = mysqli_query($con, "UPDATE `ecom_products` SET `available`='$status' WHERE `id`='$id'")or die(mysqli_error($con));
            if($result) {
                $data = 'OK';
            } else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        
        static function delete_product() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "UPDATE `ecom_products` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
            if ($result) {
                $data = json_encode([
                        "status"=>'1',
                        "message"=>"Product delete successfully."
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
