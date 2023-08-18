<?php
    include_once("generalfunctions.php");
    class DashbardFunctions
    {
        static function get_dashboard_data()
        {
            

            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            $users = mysqli_query($con, "SELECT id FROM `users` ORDER BY id");
            $data['users'] = mysqli_num_rows($users);
            $seller_id=$_SESSION['user_id'];
            if($_SESSION['role']=='3')
            {
               $products = mysqli_query($con, "SELECT id FROM `ecom_products` WHERE seller_id=$seller_id ORDER BY id");
               $data['products'] = mysqli_num_rows($products);
               if (mysqli_num_rows($products) > 0) 
              {
                $products_arr = [];
                 while($fetch = mysqli_fetch_assoc($products)) {
                    $products_arr[] = $fetch['id'];
                 } 
                    $all_pid=implode(',',$products_arr);
                  
                   /*--------------------total_orders---------------------*/
                    $total_orders = mysqli_query($con, "SELECT `ecom_orders`.`id` FROM `ecom_orders` JOIN `ecom_order_items` ON `ecom_order_items`.`order_id`=`ecom_orders`.`id` WHERE  `ecom_order_items`.`pid` IN($all_pid) GROUP BY `ecom_order_items`.`order_id`");
                    $data['total_orders'] = mysqli_num_rows($total_orders);

                    /*--------------------pending_orders---------------------*/
                    $pending_orders = mysqli_query($con, "SELECT `ecom_orders`.`id` FROM `ecom_orders` JOIN `ecom_order_items` ON `ecom_order_items`.`order_id`=`ecom_orders`.`id` WHERE  `ecom_order_items`.`pid` IN($all_pid) AND  `ecom_orders`.`order_track_status`='1'");
                    @$data['pending_orders'] = mysqli_num_rows($pending_orders);

                    /*--------------------delivered_orders---------------------*/
                    $delivered_orders = mysqli_query($con, "SELECT `ecom_orders`.`id` FROM `ecom_orders` JOIN `ecom_order_items` ON `ecom_order_items`.`order_id`=`ecom_orders`.`id` WHERE  `ecom_order_items`.`pid` IN($all_pid) AND  `ecom_orders`.`order_track_status`='2'");
                    $data['delivered_orders'] = mysqli_num_rows($delivered_orders);

                    //print_r($all_pid); die;
              }else{
                    $all_pid='';
              }  
            }else{
               $products = mysqli_query($con, "SELECT id FROM `ecom_products` ORDER BY id");
               $data['products'] = mysqli_num_rows($products);

               $total_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` ORDER BY id");
                $data['total_orders'] = mysqli_num_rows($total_orders);  

                $pending_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` WHERE order_track_status='1' ORDER BY id");
                @$data['pending_orders'] = mysqli_num_rows($pending_orders);

                $delivered_orders = mysqli_query($con, "SELECT id FROM `ecom_orders` WHERE order_track_status='2' ORDER BY id");
                $data['delivered_orders'] = mysqli_num_rows($delivered_orders);
            }
                                                                     
            mysqli_close($con);
            return $data;
        }
     

    }
?>