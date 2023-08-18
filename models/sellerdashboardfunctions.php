<?php
    include_once("generalfunctions.php");
    class SellerDashbardFunctions
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
     

    }
?>