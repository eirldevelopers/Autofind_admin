<?php
    include_once("generalfunctions.php");
    class MasterFunctions
    {
        static function get_dashboard_data()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $users = mysqli_query($con, "SELECT id FROM `users` ORDER BY id");
            $data['users'] = mysqli_num_rows($users);
         
            $products = mysqli_query($con, "SELECT id FROM `ecom_products` ORDER BY id");
            $data['products'] = mysqli_num_rows($products); 
            $total_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` ORDER BY id");
            $data['total_orders'] = mysqli_num_rows($total_orders);  

            $pending_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` WHERE order_track_status='1' ORDER BY id");
            @$data['pending_orders'] = mysqli_num_rows($pending_orders);

            $delivered_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` WHERE order_track_status='2' ORDER BY id");
            $data['delivered_orders'] = mysqli_num_rows($delivered_orders);                                                            
            mysqli_close($con);
            return $data;
        }
        static function save_product() { 
            // print_r($_POST);exit;
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $product_id = intval($_POST['product_id']);
            $category_id = intval($_POST['category_id']);
            $type_id = intval($_POST['type_id']);
            $type_name_id = $_POST['type_name_id'];
            $product_name = ucfirst(trim($_POST['product_name']));
            $description = trim(addslashes($_POST['description']));
            $remark = ucfirst(trim(addslashes($_POST['remark'])));
            $discount = (isset($_POST['discount'])?floatval($_POST['discount']):0);
            $unit_price = floatval($_POST['unit_price']);
            $unit = trim($_POST['unit']);
            $quantity = (isset($_POST['quantity'])?intval($_POST['quantity']):0);
            $video = $_FILES['video']; // Single
            $images = $_FILES['image']; // Multiple
            $duplicate = mysqli_query($con, "SELECT * FROM `products` WHERE `product_name` ='" .$product_name. "' AND category_id=".$category_id." AND id!= ".$product_id);
            if (mysqli_num_rows($duplicate) > 0) 
            {
                session_start();
                $_SESSION['msgtype'] = MSG_ERROR;
                $_SESSION['msg'] = "Duplicate product found.";  
            }
            else
            {
                if ($discount > 0) 
                {
                    $discount_price = $unit_price - ($unit_price*$discount/100);
                }
                else
                {
                    $discount_price = $unit_price;
                }
                if ($product_id == 0) // ADD Product 
                { 
                    $query = "INSERT INTO `products`(`category_id`, `product_name`, `description`, `unit_price`, `quantity`, `unit`, `type_id`, `discount`, `discount_price`, `video_file`, `remark`, `status`) VALUES (".$category_id.", '".$product_name."', '".$description."', '".$unit_price."', ".$quantity.", '".$unit."', '$type_id', '".$discount."', '".$discount_price."','', '".$remark."', ".ACTIVE.")";
                    if (mysqli_query($con, $query)) 
                    {
                        $product_id = mysqli_insert_id($con);
                        if($type_name_id) {
                            foreach ($type_name_id as $key => $value) {
                                $query = "INSERT INTO `product_sizes`(`product_id`, `product_size_id`) VALUES ('$product_id','$value')";
                                mysqli_query($con, $query)or die(mysqli_error($con));
                            }
                        }
                        mkdir("../../uploads/products/".$product_id);
                        if ($video['name'] != "") 
                        {
                            $ext = pathinfo($video['name'], PATHINFO_EXTENSION);
                            $file_path = $video['tmp_name'];
                            $video_name = "v-".$product_id.".".$ext;
                            $store = "../../uploads/products/".$product_id."/".$video_name;
                            move_uploaded_file($file_path,$store); 
                            mysqli_query($con, "UPDATE products SET video_file='".$video_name."' WHERE id=".$product_id);
                        }
                        foreach($images["tmp_name"] as $key=>$tmp_name) 
                        {
                            $file_name=$images["name"][$key];                            
                            $file_path=$images["tmp_name"][$key];
                            if ($file_name != '') 
                            {
                                $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                                $upload_query = "INSERT INTO `product_images`(`product_id`, `image_file`) VALUES (".$product_id.", 'image')";
                                if (mysqli_query($con,$upload_query)) 
                                {
                                    $upload_id = mysqli_insert_id($con);
                                    $image_file = "img-".$upload_id.".".$ext;
                                    $store = "../../uploads/products/".$product_id."/".$image_file;
                                    move_uploaded_file($file_path,$store); 
                                    mysqli_query($con,"UPDATE product_images SET image_file='".$image_file."' WHERE id=".$upload_id);
                                }
                            }
                        }
                        session_start();
                        $_SESSION['msgtype'] = MSG_SUCCESS;
                        $_SESSION['msg'] = "Product added successfully.";
                    }
                    else
                    {
                        session_start();
                        $_SESSION['msgtype'] = MSG_ERROR;
                        $_SESSION['msg'] = "Some error occured.";                        
                    }
                }
                else // Update
                {
                    $query = "UPDATE `products` SET `category_id`=".$category_id.", `product_name`='".$product_name."', `description`='".$description."', `unit_price`='".$unit_price."', `quantity`=".$quantity.", `unit`='".$unit."', `type_id`='".$type_id."', `discount`='".$discount."', `discount_price`='".$discount_price."', `remark`='".$remark."' WHERE id=".$product_id;
                    $filepath = "../../uploads/products/".$product_id;
                    if (mysqli_query($con, $query)) 
                    {
                        if($type_name_id) {
                            mysqli_query($con, "DELETE FROM product_sizes WHERE `product_id` = $product_id")or die(mysqli_error($con));
                            foreach ($type_name_id as $key => $value) {
                                $query = "INSERT INTO `product_sizes`(`product_id`, `product_size_id`) VALUES ('$product_id','$value')";
                                mysqli_query($con, $query)or die(mysqli_error($con));
                            }
                            // foreach ($type_name_id as $key => $value) {
                            //     $query = "UPDATE `product_sizes` SET `product_size_id`='$value' WHERE `product_id`='$product_id'";
                            //     mysqli_query($con, $query)or die(mysqli_error($con));
                            // }
                        }
                        if (!file_exists($filepath )) {
                            mkdir($filepath);
                        }      
                        if ($video['name'] != "") 
                        {
                            $select_product = mysqli_query($con,"SELECT * FROM products WHERE id=".$product_id);
                            $product = mysqli_fetch_assoc($select_product);
                            array_map('unlink', glob($filepath."/".$product['video_file']));
                            //------------------------------
                            $ext = pathinfo($video['name'], PATHINFO_EXTENSION);
                            $file_path = $video['tmp_name'];
                            $video_name = "v-".$product_id.".".$ext;
                            $store = $filepath."/".$video_name;
                            move_uploaded_file($file_path,$store); 
                            mysqli_query($con, "UPDATE products SET video_file='".$video_name."' WHERE id=".$product_id);
                        }
                        foreach($images["tmp_name"] as $key=>$tmp_name) 
                        {
                            $file_name=$images["name"][$key];
                            $file_path=$images["tmp_name"][$key];
                            if ($file_name != '') 
                            {
                                $ext=pathinfo($file_name,PATHINFO_EXTENSION);
                                $upload_query = "INSERT INTO `product_images`(`product_id`, `image_file`) VALUES (".$product_id.", 'image')";
                                if (mysqli_query($con,$upload_query)) 
                                {
                                    $upload_id = mysqli_insert_id($con);
                                    $image_file = "img-".$upload_id.".".$ext;
                                    $store = $filepath."/".$image_file;
                                    move_uploaded_file($file_path,$store); 
                                    mysqli_query($con,"UPDATE product_images SET image_file='".$image_file."' WHERE id=".$upload_id);
                                }  
                            } 
                        }
                        session_start();
                        $_SESSION['msgtype'] = MSG_SUCCESS;
                        $_SESSION['msg'] = "Product added successfully.";
                    }
                    else
                    {
                        session_start();
                        $_SESSION['msgtype'] = MSG_ERROR;
                        $_SESSION['msg'] = "Some error occured.";                        
                    }                    
                }
            }                  
            mysqli_close($con);
        }
        static function activate_product()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $product_id = intval($_POST['product_id']);
            $query = "UPDATE products SET status=".ACTIVE." WHERE id=".$product_id;
            if(mysqli_query($con, $query))
            {
                $success = 1;
                $msg = "Product activated successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function deactivate_product()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $product_id = intval($_POST['product_id']);
            $cart_items = mysqli_query($con,"SELECT * FROM cart_items WHERE product_id=".$product_id);
            if (mysqli_num_rows($cart_items) > 0) 
            {
                foreach ($cart_items as $cart_item) 
                {
                    mysqli_query($con,"UPDATE cart SET total_items=total_items-1 WHERE id=".$cart_item['cart_id']);
                    mysqli_query($con,"DELETE FROM cart_items WHERE id=".$cart_item['id']);              
                }
                $query = "UPDATE products SET status=".INACTIVE." WHERE id=".$product_id;
                if(mysqli_query($con, $query))
                {
                    $success = 1;
                    $msg = "Product deactivated successfully.";
                }
                else
                {
                    $msg = "Some error occured.";
                } 
            }
            else
            {
                $query = "UPDATE products SET status=".INACTIVE." WHERE id=".$product_id;
                if(mysqli_query($con, $query))
                {
                    $success = 1;
                    $msg = "Product deactivated successfully.";
                }
                else
                {
                    $msg = "Some error occured.";
                }                
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }

        static function challenge_request_status_change()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['cr_id'];
            $success = 0;
            $data = array();
            $update_query = "UPDATE challenge_request SET approval_status ='1',approval_date ='".date('Y-m-d')."' WHERE id=".$id;
            if (mysqli_query($con,$update_query)) 
            {
                $success = 1;
                $msg = "Challenge Request Change Successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }

static function get_challenge_request_list()
        {
             $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
             //echo "SELECT CR.*,U.name as customer_name FROM challenge_request CR LEFT JOIN distributors U ON CR.user_id=U.user_id  ORDER BY CR.id desc"; die;
            $result = mysqli_query($con, "SELECT CR.*,U.name as customer_name FROM challenge_request CR LEFT JOIN distributors U ON CR.user_id=U.id  ORDER BY CR.id desc");
            mysqli_close($con);
            return $result;
        }
        static function change_category_status()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $category_id = intval($_POST['category_id']);
            $select_category = mysqli_query($con,"SELECT * FROM categories WHERE id=".$category_id);
            if (mysqli_num_rows($select_category) > 0) 
            {
                $category = mysqli_fetch_assoc($select_category);
                if ($category['is_active'] == YES) 
                {
                    $products = mysqli_query($con,"SELECT * FROM products WHERE category_id=".$category_id);
                    if (mysqli_num_rows($products) > 0) 
                    {
                        foreach ($products as $product) 
                        {
                            $cart_items = mysqli_query($con,"SELECT * FROM cart_items WHERE product_id=".$product['id']);
                            if (mysqli_num_rows($cart_items) > 0) 
                            {
                                foreach ($cart_items as $cart_item) 
                                {
                                    mysqli_query($con,"UPDATE cart SET total_items=total_items-1 WHERE id=".$cart_item['cart_id']);
                                    mysqli_query($con,"DELETE FROM cart_items WHERE id=".$cart_item['id']);              
                                }
                            } 
                            mysqli_query($con,"UPDATE products SET status=".INACTIVE." WHERE id=".$product['id']);             
                        }
                    }
                    mysqli_query($con,"UPDATE categories SET is_active=".NO." WHERE id=".$category_id);
                    $success = 1;
                    $msg = "Category deactivated successfully.";
                }
                else
                {
                    mysqli_query($con,"UPDATE categories SET is_active=".YES." WHERE id=".$category_id);
                    $success = 1;
                    $msg = "Category activated successfully.";
                } 
            }
            else
            { 
                    $msg = "Some error occured.";         
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function get_products()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $products = mysqli_query($con, "SELECT P.*,C.category FROM products P INNER JOIN categories C ON P.category_id=C.id  ORDER BY C.category,P.product_name");
            mysqli_close($con);
            return $products;
        }
        static function get_product_detail($product_id)
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $product = mysqli_query($con, "SELECT P.*,C.category FROM products P INNER JOIN categories C ON P.category_id=C.id WHERE P.id=".$product_id." ORDER BY C.category,P.product_name");
            if (mysqli_num_rows($product) > 0) 
            {
               $product = mysqli_fetch_assoc($product);
            }
            else
            {
                $product = array();
            }
            mysqli_close($con);
            return $product;
        }
        static function save_category() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $category_id = intval($_POST['id']);
            $category = trim(ucfirst($_POST['category']));
            if($category_id == 0) {
                if($_FILES["image"]["name"]) {
                    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $image = 'image'.rand(0000000, 9999999). '.' .$ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/categories/". $image);
                    $query = "INSERT INTO `categories` (`category`, `image`) VALUES ('$category', '$image')";
                } else {
                    $success = 0;
                    $msg = "Please upload Image.";
                    return json_encode(array("success"=>$success,"msg"=>$msg));
                }
            } else {
                if($_FILES["image"]["name"]) {
                    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $image = 'image'.rand(0000000, 9999999). '.' .$ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/categories/". $image);
                    $query = "UPDATE categories SET category='$category', `image`='$image' WHERE id='$category_id'";
                } else {
                    $query = "UPDATE categories SET category='$category' WHERE id='$category_id'";
                }
            }
            if (mysqli_query($con, $query)) {
                $success = 1;
                $msg = "Category saved successfully";
            } else {
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }

        static function save_unit()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $unit_id = intval($_POST['id']);
            $unit = trim(ucfirst($_POST['unit']));
            $duplicate = mysqli_query($con, "SELECT * FROM units WHERE unit='".$unit."' AND id!=".$unit_id);
            if (mysqli_num_rows($duplicate) == 0) 
            {
                if ($unit_id == 0) //ADD
                {
                   $query = "INSERT INTO units (unit) VALUES ('".$unit."')";
                }
                else
                {
                    $query = "UPDATE units SET unit='".$unit."' WHERE id=".$unit_id;
                }
                if (mysqli_query($con, $query)) 
                {
                    $success = 1;
                    $msg = "Unit saved successfully";
                }
                else
                {
                    $msg = "Some error occured";
                }
            }
            else
            {
                $msg = "Duplicate unit found!!!";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function get_categories() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $categories = mysqli_query($con, "SELECT * FROM categories ORDER BY category");
            mysqli_close($con);
            return $categories;
        }

        static function get_types() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT * FROM `types` ORDER BY `id` DESC");
            mysqli_close($con);
            $categories = [];
            while($fetch = mysqli_fetch_assoc($query)) {
                $categories[] = $fetch;
            }
            return $categories;
        }

        static function save_type() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $type_id = intval($_POST['id']);
            $name = trim(ucfirst($_POST['name']));
            if ($type_id == 0) {
               $query = "INSERT INTO `types` (`name`) VALUES ('$name')";
            } else {
                $query = "UPDATE `types` SET `name` = '$name' WHERE id=".$type_id;
            }
            if (mysqli_query($con, $query)) {
                $success = 1;
                $msg = "Type saved successfully";
            } else {
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }

        static function edit_type() { 
            $id = $_POST['id'];
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT * FROM `types` where `id` = $id");
            mysqli_close($con);
            $fetch = mysqli_fetch_assoc($query);
            $data  = [
                'success' => 1,
                'data' => $fetch,
            ];
            return json_encode($data);
        }

        static function delete_type() { 
            $id = $_POST['id'];
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "DELETE FROM `types` WHERE `id` = $id");
            if($query){
                $data  = [
                    'success' => 1,
                    'message' => 'Type Delete Successfully',
                ];
            } else {
                $data  = [
                    'success' => 0,
                    'message' => 'Unable to delete type',
                ];
            }
            mysqli_close($con);
            return json_encode($data);
        }

        static function get_type_names() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT n.*, t.name as t_name FROM `type_names` n INNER JOIN `types` t on n.type_id = t.id ORDER BY `id` DESC")or die(mysqli_error($con));
            $categories = [];
            while($fetch = mysqli_fetch_assoc($query)) {
                $categories[] = $fetch;
            }
            mysqli_close($con);
            return $categories;
        }

        static function get_type_name_by_type($type_id) { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT n.*, t.name as t_name FROM `type_names` n INNER JOIN `types` t on n.type_id = t.id WHERE n.type_id = '$type_id' ORDER BY `id` DESC")or die(mysqli_error($con));
            $categories = [];
            while($fetch = mysqli_fetch_assoc($query)) {
                $categories[] = $fetch;
            }
            mysqli_close($con);
            return $categories;
        }

        static function get_product_types($id) { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT * FROM `product_sizes` where `product_id` = $id")or die(mysqli_error($con));
            $categories = [];
            while($fetch = mysqli_fetch_assoc($query)) {
                $categories[] = $fetch['product_size_id'];
            }
            mysqli_close($con);
            return $categories;
        }

        static function get_all_type() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $html = "<option value=''> - Types - </option>";
            $query = mysqli_query($con, "SELECT * FROM `types`") or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($query)) {
                $html .= "<option value='" . $fetch['id']. "'>" . $fetch['name'] ."</option>";
            }
            mysqli_close($con);
            if($query){
                $data  = [
                    'success' => 1,
                    'html' => $html,
                ];
            } else {
                $data  = [
                    'success' => 0,
                ];
            }
            return json_encode($data);
        }

        static function get_type_names_in_ajax() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $html = "<option value=''> - Size - </option>";
            $query = mysqli_query($con, "SELECT * FROM `type_names` WHERE `type_id` = '$id'") or die(mysqli_error($con));
            while($fetch = mysqli_fetch_assoc($query)) {
                $html .= "<option value='" . $fetch['id']. "'>" . $fetch['name'] ."</option>";
            }
            mysqli_close($con);
            if($query){
                $data  = [
                    'success' => 1,
                    'html' => $html,
                ];
            } else {
                $data  = [
                    'success' => 0,
                ];
            }
            return json_encode($data);
        }

        static function save_type_name() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = intval($_POST['id']);
            $type_id = intval($_POST['type_id']);
            $name = trim(ucfirst($_POST['name']));
            if ($id == 0) {
               $query = "INSERT INTO `type_names` (`type_id`, `name`) VALUES ('$type_id', '$name')";
            } else {
                $query = "UPDATE `type_names` SET `name` = '$name' , `type_id` = '$type_id' WHERE id = '$id'";
            }
            if (mysqli_query($con, $query)) {
                $success = 1;
                if($id == 0) {
                    $msg = "Type saved successfully";
                } else {
                    $msg = "Type update successfully";
                }
            } else {
                $success = 0;
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }

        static function edit_type_name() { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $query = mysqli_query($con, "SELECT * FROM `type_names` where `id` = $id")or die(mysqli_error($con));
            $fetch = mysqli_fetch_assoc($query);
            $html = "<option value=''> - Types - </option>";
            $type_query = mysqli_query($con, "SELECT * FROM `types`") or die(mysqli_error($con));
            while($type_fetch = mysqli_fetch_assoc($type_query)) {
                if($type_fetch['id'] == $fetch['type_id']) {
                    $html .= "<option selected value='" . $type_fetch['id']. "'>" . $type_fetch['name'] ."</option>";
                }
                $html .= "<option value='" . $type_fetch['id']. "'>" . $type_fetch['name'] ."</option>";
            }
            $data  = [
                'success' => 1,
                'data' => $fetch,
                'html' => $html,
            ];
            
            mysqli_close($con);
            return json_encode($data);
        }

        static function delete_type_name() { 
            $id = $_POST['id'];
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "DELETE FROM `type_names` WHERE `id` = $id");
            if($query){
                $data  = [
                    'success' => 1,
                    'message' => 'Type Delete Successfully',
                ];
            } else {
                $data  = [
                    'success' => 0,
                    'message' => 'Unable to delete type',
                ];
            }
            mysqli_close($con);
            return json_encode($data);
        }

        static function get_rank()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $rank = mysqli_query($con, "SELECT * FROM rank ORDER BY rank");
            mysqli_close($con);
            return $rank;
        }
        static function save_rank()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $rank_id = intval($_POST['id']);
            $rank = trim(ucfirst($_POST['rank']));
            $rank_short = trim(ucfirst($_POST['rank_short']));
            $msd_percent = trim($_POST['msd_percent']);
            $duplicate = mysqli_query($con, "SELECT * FROM rank WHERE rank='".$rank."' AND id!=".$rank_id);
            if (mysqli_num_rows($duplicate) == 0) 
            {
                if ($rank_id == 0) //ADD
                {
                   $query = "INSERT INTO rank (rank, rank_short, msd_percent) VALUES ('".$rank."', '".$rank_short."', '".$msd_percent."')";
                }
                else
                {
                    $query = "UPDATE rank SET rank='".$rank."', rank_short='".$rank_short."', msd_percent='".$msd_percent."' WHERE id=".$rank_id;
                }
                if (mysqli_query($con, $query)) 
                {
                    $success = 1;
                    $msg = "Rank saved successfully";
                }
                else
                {
                    $msg = "Some error occured";
                }
            }
            else
            {
                $msg = "Duplicate rank found!!!";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function get_distributors()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $distributors = mysqli_query($con, "SELECT D.*, COALESCE((SELECT rank_short FROM rank WHERE id=D.rank_id ),'N/A') as rank FROM distributors D ORDER BY D.id desc");
            mysqli_close($con);
            return $distributors;
        }

        static function get_points_history()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $points = mysqli_query($con, "SELECT * FROM distributors_points_log D ORDER BY D.id desc");
            mysqli_close($con);
            return $points;
        }

        static function get_distributor_profile($distributor_id)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $query = mysqli_query($con, "SELECT D.*,COALESCE((SELECT rank FROM rank WHERE id=D.rank_id),'') as rank FROM distributors D WHERE D.id=".$distributor_id);
            $profile = mysqli_fetch_assoc($query);
            mysqli_close($con);
            return $profile;            
        }
        static function update_distributor_points()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $distributor_id = intval($_POST['distributor_id']);
            $add_points = intval($_POST['add_points']);

            
            $distributors_res =mysqli_fetch_assoc(mysqli_query($con, "SELECT points FROM `distributors` WHERE id='".$distributor_id."'"));
             //print_r($distributors_res); die;
           
            if (is_int($add_points) && $add_points < 1) {
                    $actual_point=$distributors_res['points']-abs($add_points);
                $points='points="'.$actual_point.'"';
                $txn_type=2;
                }

            if (is_int($add_points) && $add_points > 0) {
                    $actual_point=$distributors_res['points']+$add_points;
                $points='points="'.$actual_point.'"';
                $txn_type=1;
                }
                    
                $query = "UPDATE distributors SET $points WHERE id=".$distributor_id;
            if (mysqli_query($con, $query)) 
            {
                if(mysqli_query($con,"INSERT INTO `distributors_points_log`( `distributor_id`, `points`, `coupon_code`,`txn_type`) VALUES (".$distributor_id.", ".$add_points.", '', ".$txn_type.") "))
                {
                    $result =mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `site_setting`"));
                    $coupon_id = mysqli_insert_id($con);
                    $coupon_code = $result['short_name'].$coupon_id;
                    mysqli_query($con,"UPDATE distributors_points_log SET coupon_code='".$coupon_code."' WHERE id=".$coupon_id);
                }
                $success = 1;
                $msg = "Points Updated successfully";
            }
            else
            {
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function update_distributor_profile()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $distributor_id = intval($_POST['distributor_id']);
            $name = ucfirst(trim($_POST['name']));
            $mobile_no = trim($_POST['mobile_no']);
            $email_id = trim($_POST['email_id']);
            $query = "UPDATE distributors SET mobile_no='".$mobile_no."', email_id='".$email_id."' WHERE id=".$distributor_id;
            if (mysqli_query($con, $query)) 
            {
                $success = 1;
                $msg = "Profile Updated successfully";
            }
            else
            {
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function update_distributor_details()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            //print_r($_POST);exit;
            $distributor_id = intval($_POST['id']);
            $name = ucfirst(trim($_POST['name']));
            $mobile_no = trim($_POST['mobile_no']);
            $email_id = trim($_POST['email_id']);
            $gender = intval($_POST['gender']);
            $account_no = trim($_POST['account_no']);
            $pan_no = trim($_POST['pan_no']);
            $ifsc_code = trim($_POST['ifsc_code']);
            $address = trim($_POST['address']);
            $query = "UPDATE distributors SET mobile_no='".$mobile_no."', email_id='".$email_id."',name='".$name."',gender=".$gender.",account_no='".$account_no."',pan_no='".$pan_no."',ifsc_code='".$ifsc_code."',address='".$address."' WHERE id=".$distributor_id;
            if (mysqli_query($con, $query)) 
            {
                $_SESSION['msgtype'] = MSG_SUCCESS;
                $_SESSION['msg'] = "Profile updated successfully.";
            }
            else
            {
                $_SESSION['msgtype'] = MSG_ERROR;
                $_SESSION['msg'] = "Some error occurred!!!\nPlease try again.";
            }
            mysqli_close($con);
            return 1;
        }
        static function reset_distributor_password()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $success = 0;
            $msg = "";
            $data = array(); 
            $distributor_id = intval($_POST['distributor_id']);
            $distributor = mysqli_query($con, "SELECT * FROM distributors WHERE id=".$distributor_id);
            $row = mysqli_fetch_assoc($distributor);
            //$name=md5($row['name'].'@12345');
            $name=md5(12345);
            $query = "UPDATE distributors set password='".$name."' where id=".$distributor_id;
            if (mysqli_query($con, $query)) 
            {
                $success = 1;
                $msg = "Password Reset successfully";
            }
            else
            {
                $msg = "Some error occured";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success,"msg"=>$msg));
        }
        static function get_units()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $units = mysqli_query($con, "SELECT * FROM units ORDER BY unit");
            mysqli_close($con);
            return $units;
        }
        static function get_product_images($product_id)
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $products = mysqli_query($con, "SELECT * FROM product_images WHERE product_id=".$product_id);
            mysqli_close($con);
            return $products;
        }  
        static function delete_product_image()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $product_id = intval($_POST['product_id']);
            $image_id = intval($_POST['image_id']);
            $data = array();
            $query = "DELETE FROM product_images WHERE id=".$image_id;
            if (mysqli_query($con, $query)) 
            {
                $filepath = "../../uploads/products/".$product_id."/img-".$image_id;
                array_map('unlink', glob($filepath.".*"));
                $data = json_encode(array("success"=>1,"msg"=>"Image deleted successfully"));
            }
            else
            {
                $data = json_encode(array("success"=>0,"msg"=>"Some error occured"));
            }
            mysqli_close($con);
            return $data;
        }
        static function get_users()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $users = mysqli_query($con, "SELECT * FROM users ORDER BY id desc");
            mysqli_close($con);
            return $users;
        }
        static function change_user_status()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $user_id = intval($_POST['id']);
            $success = 0;
            $data = array();
            $users = mysqli_query($con, "SELECT * FROM users WHERE id=".$user_id);
            $row = mysqli_fetch_assoc($users);
            if ($row['acnt_status'] == AS_ACTIVE) 
            {
                $update_query = "UPDATE users SET acnt_status = ".AS_INACTIVE." WHERE id=".$user_id;
            }
            else
            {
                $update_query = "UPDATE users SET acnt_status = ".AS_ACTIVE." WHERE id=".$user_id;
            }
            if (mysqli_query($con,$update_query)) 
            {
                $success = 1;
                $msg = "Status changed successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }
        static function change_distributor_status()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $distributor_id = intval($_POST['id']);
            $success = 0;
            $data = array();
            $users = mysqli_query($con, "SELECT * FROM distributors WHERE id=".$distributor_id);
            $row = mysqli_fetch_assoc($users);
            if ($row['acnt_status'] == AS_ACTIVE) 
            {
                $update_query = "UPDATE distributors SET acnt_status = ".AS_INACTIVE." WHERE id=".$distributor_id;
            }
            else
            {
                $update_query = "UPDATE distributors SET acnt_status = ".AS_ACTIVE." WHERE id=".$distributor_id;
            }
            if (mysqli_query($con,$update_query)) 
            {
                $success = 1;
                $msg = "Status changed successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }
        static function update_distributor_rank()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $distributor_id = intval($_POST['id']);
            $rank_id = intval($_POST['rank']);
            $query = "UPDATE distributors SET rank_id=".$rank_id." WHERE id=".$distributor_id;
            if(mysqli_query($con,$query))
            {
                $_SESSION['msgtype'] = MSG_SUCCESS;
                $_SESSION['msg'] = "Password changed successfully.";
            }
            else
            {
                $_SESSION['msgtype'] = MSG_ERROR;
                $_SESSION['msg'] = "Some error occurred!!!\nPlease try again.";
            }
            mysqli_close($con);
        }
        static function get_level_wise_percentage()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $data = mysqli_query($con, "SELECT * FROM level_wise_percentage  ORDER BY level asc");
            mysqli_close($con);
            return $data;
        }
        static function save_level_wise_percentage()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $level = intval($_POST['level']);
            $percentage = trim($_POST['percentage']);
            $data = mysqli_query($con, "SELECT * FROM level_wise_percentage WHERE level=".$level);
            if (mysqli_num_rows($data) == 0) 
            {
                if (mysqli_query($con," INSERT INTO level_wise_percentage (level, percentage) VALUES (".$level.", '".$percentage."') ")) 
                {
                    $success = 1;
                    $msg = "Saved successfully.";
                }
                else
                {
                    $success = 0;
                    $msg = "Some error occured.";
                }
            }
            else
            {
                if (mysqli_query($con,"UPDATE level_wise_percentage SET percentage = '".$percentage."' WHERE level=".$level)) 
                {
                    $success = 1;
                    $msg = "Updated successfully.";
                }
                else
                {
                    $success = 0;
                    $msg = "Some error occured.";
                }                
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }
        static function get_orders()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $orders = mysqli_query($con, "SELECT O.*,UA.name as customer_name,UA.mobile_no as customer_mobile,UA.address,UA.pincode,UA.state,UA.city FROM orders O LEFT JOIN users_address UA ON O.user_id=UA.user_id WHERE status!='0' ORDER BY O.id desc");
            mysqli_close($con);
            return $orders;
        }

        static function get_cancel_orders()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $orders = mysqli_query($con, "SELECT O.*,UA.name as customer_name,UA.mobile_no as customer_mobile,UA.address,UA.pincode,UA.state,UA.city FROM orders O LEFT JOIN users_address UA ON O.user_id=UA.user_id WHERE status='0' ORDER BY O.id desc");
            mysqli_close($con);
            return $orders;
        }
        static function get_ordered_products($order_id)
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $items = mysqli_query($con, "SELECT P.id as product_id, P.product_name, C.category,coalesce((SELECT image_file FROM product_images WHERE product_id=P.id LIMIT 1),'') as img , OI.unit_price, OI.unit, OI.discount, OI.discount_price, OI.quantity FROM order_items OI INNER JOIN products P ON OI.product_id=P.id INNER JOIN categories C ON P.category_id=C.id WHERE OI.order_id=".$order_id." ORDER BY OI.id");
            mysqli_close($con);
            return $items;
        }
        static function mark_product_delivered()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $success = 0;
            $data = array();
            $update_query = "UPDATE orders SET status = ".ORDER_DELIVERED." WHERE id=".$order_id;
            if (mysqli_query($con,$update_query)) 
            {
                $success = 1;
                $msg = "Order marked delivered.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }
        static function cancel_order()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $success = 0;
            $data = array();
            $update_query = "UPDATE orders SET status = ".ORDER_CANCELLED." WHERE id=".$order_id;
            if (mysqli_query($con,$update_query)) 
            {
                $success = 1;
                $msg = "Order cancelled successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }
        static function get_invoice($user_id, $order_id)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $data = array();
            if ($user_id == 0) 
            {
                $user_id = isset($_SESSION['rw_user']) ? intval($_SESSION['user_id']) : 0;
            }
            $orders = mysqli_query($con,"SELECT o.*, u.name as customer_name, u.address, u.city, u.state, u.pincode, u.mobile_no as customer_mobile FROM orders o INNER JOIN users_address u ON o.address_id=u.id WHERE o.user_id=".$user_id." AND o.id=".$order_id." ORDER BY o.id") or die(mysqli_error($con));
            if (mysqli_num_rows($orders) > 0) 
            {
                $order = mysqli_fetch_assoc($orders);
                $order['saving'] = 0;
                $items = mysqli_query($con,"SELECT P.id,P.product_name,P.description,OI.unit_price,OI.discount,OI.discount_price,OI.quantity,OI.unit,PS.name as product_size, coalesce((SELECT image_file FROM product_images PI WHERE PI.product_id=P.id LIMIT 1), '') img FROM products P INNER JOIN order_items OI ON OI.product_id=P.id INNER JOIN type_names PS ON OI.product_size_id=PS.id WHERE OI.order_id=".$order['id']);
                foreach ($items as $item) 
                {
                    $order['saving'] += $item['unit_price']-$item['discount_price'];
                    $order['items'][] = $item;
                }
                $data = $order;
            }
            mysqli_close($con);
            return $data;
        } 

         static function calculation_commission()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $amount = $_POST['amount'];
            $user_id = $_POST['user_id'];
            $commission=0;
            $res_user_count=0;
            $profile_rank='';
            $query_res = mysqli_query($con, "SELECT distributors.member_id,rank.rank_short,rank.msd_percent FROM distributors LEFT JOIN rank ON distributors.rank_id=rank.id WHERE distributors.id='$user_id'");
                    $res_user = mysqli_num_rows($query_res);
            if($res_user > 0)
            {
                $row = mysqli_fetch_assoc($query_res);
                $member_id =$row['member_id'];
                $profile_rank=$row['rank_short'];
            /*----------------Commission Calculation-------------------*/
                $total_amount=$amount;
            /*----------------------Total User Count--------------------*/ 
              $res = mysqli_query($con, "SELECT count(*) as total_user FROM distributors WHERE sponsor_id='$member_id'");
                    if (mysqli_num_rows($res) >0) 
                    {
                        $data = mysqli_fetch_assoc($res);
                        $res_user_count=$data['total_user'];
                    }
              
                if($profile_rank=='BA')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }else if($profile_rank=='CT')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }else if($profile_rank=='LE')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($profile_rank=='AM')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($profile_rank=='BM')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($profile_rank=='BO')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($profile_rank=='ABO')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }
    }

  
/*---------------------------------------------------------*/

            $success = 0;
            $data = array();

            $res_comm = mysqli_query($con, "SELECT commission FROM orders WHERE id='".$order_id."'");
            $res_comm_count = mysqli_num_rows($res_comm);
                         if($res_comm_count > 0)
                         {
                            $row_comm = mysqli_fetch_assoc($res_comm);
                           if(round($row_comm['commission']) > 0 )
                           {
                             $msg = "Commission already updated.";
                             return json_encode(array("success"=>$success, "msg"=> $msg));
                             die(); 
                           }
                         }
            $update_query = "UPDATE orders SET commission = ".$commission." WHERE id=".$order_id; 
            if (mysqli_query($con,$update_query)) 
            {
            /*------------------BA level rank update--------------*/
                  if($res_user_count >= 4 && $profile_rank=='BA')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='CT'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }

                /*------------------CT level rank update--------------*/
                  if($res_user_count >= 7 && $profile_rank=='CT')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='EL'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }

    /*=============================[EL level]=============================*/
                    /*-------------rank update EL level-----------------*/
                     if($res_user_count > 11 && $profile_rank=='EL')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT * FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['approval_status']==1)
                              {
        /*-----------[condition-1 (+45day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+45 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-45day)] accroding-----------*/
                                $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-45 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                             }        
            

                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='AM'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='AM'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    }
/*=============================[AM level]===================================*/
                    /*-------------rank update AM level-----------------*/
                     if(!empty($order_id) && ($res_user_count >= 12 && $profile_rank=='AM'))
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT * FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['approval_status']==1)
                              {
        /*-------[condition-1 (+45day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-90day)]  accroding---------*/
                                $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                                }
                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BM'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BM'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    


/*=============================[BM level]===================================*/
                    /*-------------rank update BM level-----------------*/
                     if($res_user_count >= 16 && $profile_rank=='BM')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT * FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['approval_status']==1)
                              {
        /*-------[condition-1 (+90day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-90day)]  accroding---------*/
                                $last_total_business_amount=0;
                                 $last_end_date='';
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                    $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                    $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
            
                                }
                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BO'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BO'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                            }
                        
                       }
                    }
/*=============================[BO level]===================================*/
/*-------------rank update BO level-----------------*/
                     if($res_user_count >= 20 && $profile_rank=='BO')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT * FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['approval_status']==1)
                              {
        /*-----------[condition-1 (+90day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-90day)] accroding-----------*/
                                $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                             }        
            

                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='ABO'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='ABO'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    } 

                $success = 1;
                $msg = "Order commission update successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }


        static function calculation_commission_07_02_2023()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $amount = $_POST['amount'];
            $user_id = $_POST['user_id'];
            $commission=0;
            $res_user_count=0;
            $query_res = mysqli_query($con, "SELECT distributors.member_id,rank.rank_short,rank.msd_percent FROM distributors LEFT JOIN rank ON distributors.rank_id=rank.id WHERE distributors.id='$user_id'");
                    $res_user = mysqli_num_rows($query_res);
            if($res_user > 0)
            {
                $row = mysqli_fetch_assoc($query_res);
                $member_id =$row['member_id'];
            /*----------------Commission Calculation-------------------*/
                $total_amount=$amount;
            /*----------------------Total User Count--------------------*/ 
              $res = mysqli_query($con, "SELECT count(*) as total_user FROM distributors WHERE sponsor_id='$member_id'");
                    if (mysqli_num_rows($res) >0) 
                    {
                        $data = mysqli_fetch_assoc($res);
                        $res_user_count=$data['total_user'];
                    }
              
                if($row['rank_short']=='BA')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }else if($row['rank_short']=='CT')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }else if($row['rank_short']=='EL')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($row['rank_short']=='AM')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($row['rank_short']=='BM')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($row['rank_short']=='BO')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }else if($row['rank_short']=='ABO')
        {
        if($res_user_count >= 1)
        {
                $commission_rate = $row['msd_percent']; 
                $commission = ($total_amount * $commission_rate) / 100;     
        }
      }
    }

  
/*---------------------------------------------------------*/

            $success = 0;
            $data = array();

            $res_comm = mysqli_query($con, "SELECT commission FROM orders WHERE id='".$order_id."'");
            $res_comm_count = mysqli_num_rows($res_comm);
                         if($res_comm_count > 0)
                         {
                            $row_comm = mysqli_fetch_assoc($res_comm);
                           if(round($row_comm['commission']) > 0 )
                           {
                             $msg = "Commission already updated.";
                             return json_encode(array("success"=>$success, "msg"=> $msg));
                             die(); 
                           }
                         }
            $update_query = "UPDATE orders SET commission = ".$commission." WHERE id=".$order_id; 
            if (mysqli_query($con,$update_query)) 
            {
            /*------------------BA level rank update--------------*/
                  if($res_user_count >= 4 && $row['rank_short']=='BA')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='CT'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }

                /*------------------BA level rank update--------------*/
                  if($res_user_count >= 3 && $row['rank_short']=='CT')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='EL'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }

    /*=============================[EL level]=============================*/
                    /*-------------rank update EL level-----------------*/
                     if($res_user_count > 4 && $row['rank_short']=='EL')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT id,rank_id,range,range_amount,approval_status,approval_date,rank_short_name FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['approval_status']==1)
                              {
        /*-----------[condition-1 (+45day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+45 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-45day)] accroding-----------*/
                                $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-45 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                             }        
            

                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='AM'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='AM'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    }
/*=============================[AM level]===================================*/
                    /*-------------rank update AM level-----------------*/
                     if($res_user_count >= 1 && $row['rank_short']=='AM')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT id,rank_id,range,range_amount,approval_status,approval_date,rank_short_name FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['status']==1)
                              {
        /*-------[condition-1 (+45day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-90day)]  accroding---------*/
                                $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                                }
                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BM'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BM'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    }


/*=============================[BM level]===================================*/
            /*-------------rank update BM level-----------------*/
                     if($res_user_count >= 4 && $row['rank_short']=='BM')
                    {

            /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT id,rank_id,range,range_amount,approval_status,approval_date,rank_short_name FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['status']==1)
                              {
        /*-------[condition-1 (+90day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }
        /*-----------[condition-2 (current_date-90day)]  accroding---------*/                    $last_total_business_amount=0;
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                 $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                 $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
                                }  
                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BO'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='BO'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    }

/*=============================[BO level]===================================*/
                    /*-------------rank update BO level-----------------*/
                     if($res_user_count >= 4 && $row['rank_short']=='BO')
                    {

                    /*-----------challange request status approved-------*/
                      $res_cr = mysqli_query($con, "SELECT id,rank_id,range,range_amount,approval_status,approval_date,rank_short_name FROM challenge_request WHERE user_id='$user_id' AND rank_short_name='".$row['rank_short']."'");
                        $cr_count = mysqli_num_rows($res_cr);
                         if($cr_count>0)
                        {
                           {
                            $row_cr = mysqli_fetch_assoc($res_cr);
                            if($row_cr['status']==1)
                              {
        /*-------[condition-1 (+90day)]approval date accroding---------*/
                                $total_business_amount=0;
                                $start_date=$row_cr['approval_date'];
                                $end_date=date("Y-m-d",strtotime($start_date."+90 day"));
                                 $query= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) >= '".$start_date."' AND DATE(order_datetime) <= '".$end_date."'"; 
                                 $query_business_value = mysqli_query($con,$query);
                                 if (mysqli_num_rows($query_business_value) >0) 
                                    {
                                        $res_bv = mysqli_fetch_assoc($query_business_value);
                                        $total_business_amount=$res_bv['total_amount'];
                                    }

        /*-----------[condition-2 (current_date-90day)]  accroding---------*/
                                $last_total_business_amount=0;
                                 $last_end_date='';
                                if($end_date < date('Y-m-d'))
                                {
                                    $last_end_date=date('Y-m-d');
                                    $start_date=date("Y-m-d",strtotime($last_end_date."-90 day"));
                                    $query_last= "SELECT SUM(`total_amount`) as  total_amount FROM orders WHERE user_id IN (SELECT id from distributors WHERE sponsor_id='".$member_id."') AND DATE(order_datetime) <= '".$last_end_date."' AND DATE(order_datetime) >= '".$start_date."'"; 
                                    $query_business_value_last = mysqli_query($con,$query_last);
                                 if (mysqli_num_rows($query_business_value_last) >0) 
                                    {
                                        $res_bvl = mysqli_fetch_assoc($query_business_value_last);
                                        $last_total_business_amount=$res_bvl['total_amount'];
                                    }
            
                                }
                        if($total_business_amount >= $row_cr['range_amount'])
                           {
                            $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='ABO'");
                            $rank_count = mysqli_num_rows($res_rank);
                            if($rank_count>0)
                               {
                                    $row = mysqli_fetch_assoc($res_rank);
                                    mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                      mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                }

                               }else if($last_total_business_amount >=$row_cr['range_amount']){
                                     $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='ABO'");
                                    $rank_count = mysqli_num_rows($res_rank);
                                    if($rank_count>0)
                                       {
                                            $row = mysqli_fetch_assoc($res_rank);
                                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                                        }
                                }

                              }
                           }
                       }
                    }
    

                $success = 1;
                $msg = "Order commission update successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }

        static function calculation_commission_06_02_2023()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $amount = $_POST['amount'];
            $user_id = $_POST['user_id']; 
            $commission=0;
            $res_user_count=0;
            $query_res = mysqli_query($con, "SELECT distributors.member_id,rank.rank_short,rank.msd_percent FROM distributors LEFT JOIN rank ON distributors.rank_id=rank.id WHERE distributors.id='$user_id'");
                    $res_user = mysqli_num_rows($query_res);
            if($res_user > 0)
            {
                $row = mysqli_fetch_assoc($query_res);
                $member_id =$row['member_id'];
            /*----------------Commission Calculation-------------------*/
                $total_amount=$amount;
            /*----------------------Total User Count--------------------*/ 
              $res = mysqli_query($con, "SELECT count(*) as total_user FROM distributors WHERE sponsor_id='$member_id'");
                    if (mysqli_num_rows($res) >0) 
                    {
                        $data = mysqli_fetch_assoc($res);
                        $res_user_count=$data['total_user'];
                    }
              
                if($row['rank_short']=='BA')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }

                   if($row['rank_short']=='CT')
                {
              
                    if($res_user_count >= 1)
                    {
                            $commission_rate = $row['msd_percent']; 
                            $commission = ($total_amount * $commission_rate) / 100;
                           

                    }

              }
    }

  
/*---------------------------------------------------------*/

            $success = 0;
            $data = array();
            $res_comm = mysqli_query($con, "SELECT commission FROM orders WHERE id='".$order_id."'");
            $res_comm_count = mysqli_num_rows($res_comm);
                         if($res_comm_count > 0)
                         {
                            $row_comm = mysqli_fetch_assoc($res_comm);
                           if(round($row_comm['commission']) > 0 )
                           {
                             $msg = "Commission already updated.";
                             return json_encode(array("success"=>$success, "msg"=> $msg));
                             die(); 
                           }
                         }
            $update_query = "UPDATE orders SET commission = ".$commission." WHERE id=".$order_id; 
            if (mysqli_query($con,$update_query)) 
            {
                 /*------------------BA level rank update--------------*/
                  if($res_user_count >= 4 && $row['rank_short']=='BA')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='CT'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }

                /*------------------BA level rank update--------------*/
                  if($res_user_count >= 3 && $row['rank_short']=='CT')
                    {
                        $res_rank = mysqli_query($con, "SELECT id,rank_short,rank FROM rank WHERE rank_short='EL'");
                         $rank_count = mysqli_num_rows($res_rank);
                         if($rank_count>0)
                         {
                            $row = mysqli_fetch_assoc($res_rank);
                            mysqli_query($con, "UPDATE distributors SET rank_id='".$row['id']."' WHERE id= '$user_id'");
                              mysqli_query($con, "INSERT INTO `user_rank_log`( `user_id`, `rank_id`, `rank_short_name`, `rank`) VALUES (".$user_id.",".$row['id'].",'".$row['rank_short']."','".$row['rank']."')");
                          }
                    
                    }    



                $success = 1;
                $msg = "Order commission update successfully.";
            }
            else
            {
                $msg = "Some error occured.";
            }
            mysqli_close($con);
            return json_encode(array("success"=>$success, "msg"=> $msg));
        }



    }
?>