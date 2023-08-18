<?php
   // require("../models/mainfunctions.php");
   require("../models/orderfunctions.php");
     
    $task_post = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task_post);
    
    switch($task)
    {
        
        case "update_order" :
            $response = OrderFunctions::update_order();
            echo $response;
            break;    

        case "delete_order" :
            $response = OrderFunctions::delete_order();
            echo $response;
            break;  

        case "get_order" :
            $response = OrderFunctions::get_order();
            echo $response;
            break;  

        case "cancel_order" :
            $response = OrderFunctions::cancel_order(); 
            echo $response;
            break;
                        
        case "mark_product_delivered" :
            $response = OrderFunctions::mark_product_delivered(); 
            echo $response;
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>