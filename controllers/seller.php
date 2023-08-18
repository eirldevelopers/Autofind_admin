<?php
    require("../models/sellerfunctions.php");
    
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    
    switch($task)
    {
        case "create_user_login" :
            $response = SellerFunctions::create_user_login();
            echo $response;
            
            break;
            
        case "change_user_status" :
            $response = SellerFunctions::change_user_status();
            echo $response;
            
            break;
            
        case "reset_user_password" :
            $response = SellerFunctions::reset_user_password();
            echo $response;
            
            break;
        
        case "save_menu_permission" :
            SellerFunctions::save_menu_permission();
            header("Location: ../views/users.php");
            
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>