<?php
    include_once("generalfunctions.php");
    
    class OrderFunctions
    {
       static function get_order() {
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
               
               $result = mysqli_query($con, "SELECT ecom_orders.id as order_id,ecom_orders.*,users.*,SUM(`ecom_order_items`.`unit_price`) as total_order_amt FROM `ecom_orders` JOIN `ecom_order_items` ON `ecom_order_items`.`order_id`=`ecom_orders`.`id` LEFT JOIN `users` ON `ecom_orders`.`user_id`=`users`.`id` WHERE  `ecom_order_items`.`pid` IN($all_pid)  GROUP BY `ecom_order_items`.`order_id` order by `ecom_orders`.`id` DESC");
            }else{
            
            $result = mysqli_query($con, "SELECT ecom_orders.id as order_id,ecom_orders.*,users.*,SUM(`ecom_order_items`.`unit_price`) as total_order_amt FROM `ecom_orders` LEFT JOIN `users` ON `ecom_orders`.`user_id`=`users`.`id` LEFT JOIN `ecom_order_items` ON `ecom_orders`.`id`=`ecom_order_items`.`order_id` order by `ecom_orders`.`id` DESC")or die(mysqli_error($con));
            }
            mysqli_close($con);
            return $result;
        }

       

        static function change_order_status() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $status = $_POST['status'];
            $id = $_POST['item_id'];
            $result = mysqli_query($con, "UPDATE `ecom_orders` SET `order_track_status`='$status' WHERE `id`='$id'")or die(mysqli_error($con));

            if($result) {
                $data = 'OK';
            }
            else {
                $data = 'NOT';
            }
            mysqli_close($con);
            return $data;
        }

        static function edit_order() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
            $result = mysqli_query($con, "SELECT * FROM `ecom_orders` WHERE `id`='$id'");
            $row = mysqli_fetch_array($result);
            mysqli_close($con);
            return $row;
        }

       
        static function create_order() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            //print_r($_POST); die;
           $order =$_POST['order'];
           $order_title =$_POST['order_title'];
           $order_description =$_POST['order_description'];
           $order_type =$_POST['order_type'];
           $number_of_time_usage =$_POST['number_of_time_usage'];
           $per_user =$_POST['per_user'];
           $discount =$_POST['discount'];
           $expiry_date =$_POST['expiry_date'];
          
            $result = mysqli_query($con, "INSERT INTO `ecom_orders`(`order`,`order_title`,`order_description`,`number_of_time_usage`,`per_user`,`order_type`,`discount`,`expiry_date`) VALUES ('$order','$order_title','$order_description','$number_of_time_usage','$per_user','$order_type','$discount','$expiry_date')");
                if($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"order create successfully."
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

        static function update_order() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $result = '';
            $order_one_id = $_POST['order_one_id'];
            $order =$_POST['edit_order'];
            $order_title =$_POST['edit_order_title'];
            $order_description =$_POST['edit_order_description'];
            $order_type =$_POST['edit_order_type'];
            $number_of_time_usage =$_POST['edit_number_of_time_usage'];
            $per_user =$_POST['edit_per_user'];
            $discount =$_POST['edit_discount'];
            $expiry_date =$_POST['edit_expiry_date'];
            $result = mysqli_query($con, "UPDATE `ecom_orders` SET  `order`='$order',`order_title`='$order_title',`order_description`='$order_description',`order_type`='$order_type',`number_of_time_usage`='$number_of_time_usage',`per_user`='$per_user',`discount`='$discount',`expiry_date`='$expiry_date' WHERE `id`='$order_one_id'");
            
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"order update successfully."
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

        static function delete_order() {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $id = $_POST['id'];
                $result = mysqli_query($con, "UPDATE `ecom_orders` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
                if ($result) {
                    $data = json_encode([
                            "status"=>'1',
                            "message"=>"order delete successfully."
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

        static function mark_product_delivered()
        { 
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $order_id = intval($_POST['order_id']);
            $success = 0;
            $data = array();
            $update_query = "UPDATE ecom_orders SET order_track_status = '2' WHERE id=".$order_id;
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
            $update_query = "UPDATE ecom_orders SET order_track_status = '3' WHERE id=".$order_id;
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
            //echo "SELECT o.*, u.first_name as customer_name, u.address, u.city, u.state, u.postcode as pincode, u.phone  as customer_mobile FROM ecom_orders o INNER JOIN users u ON o.id=u.id WHERE o.user_id=".$user_id." AND o.id=".$order_id." ORDER BY o.id"; die;

            $orders = mysqli_query($con,"SELECT o.*, u.first_name as customer_name, u.address, u.city, u.state, u.postcode as pincode, u.phone as customer_mobile FROM ecom_orders o LEFT JOIN users u ON o.id=u.id WHERE  o.id=".$order_id." ORDER BY o.id");
        
            if (mysqli_num_rows($orders) > 0) 
            {
                $order = mysqli_fetch_assoc($orders);
                $order['saving'] = 0;

               // echo"SELECT P.id,P.product_name,P.description,OI.quantity,OI.unit_price,OI.item_price coalesce((SELECT search_image as image_file FROM ecom_product_images PI WHERE PI.pid=P.id LIMIT 1), '') img FROM ecom_products P INNER JOIN ecom_order_items OI ON OI.product_id=P.id  WHERE OI.order_id=".$order['id']; die;
                $items = mysqli_query($con,"SELECT P.id,P.product_name,P.short_description,OI.quantity,OI.unit_price,OI.item_price, coalesce((SELECT search_image as image_file FROM ecom_product_images PI WHERE PI.pid=P.id LIMIT 1), '') img FROM ecom_products P INNER JOIN ecom_order_items OI ON OI.pid=P.id WHERE OI.order_id=".$order['id']);
                  
               
                if(!empty($items))
                {
                foreach ($items as $item) 
                {
                    $order['saving'] += $item['unit_price'];
                    $order['items'][] = $item;
                }
                 }
                $data = $order;
            }
            mysqli_close($con);
            return $data;
        } 
 }