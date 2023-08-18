<?php
    require("../models/adminfunctions.php");
    
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    //print_r($_POST); die;
     if(isset($_POST['utype']) && $_POST['utype']==2)
            {
             $location=header("Location: ../views/seller.php");
            }else{
             $location= header("Location: ../views/users.php");                  
            }
    switch($task)
    {
        case "create_user_login" :
            $response = AdminFunctions::create_user_login();
            echo $response;
            
            break;
            
        case "change_user_status" :
            $response = AdminFunctions::change_user_status();
            echo $response;
            
            break;
            
        case "reset_user_password" :
            $response = AdminFunctions::reset_user_password();
            echo $response;
            
            break;
        
        case "save_menu_permission" :
            AdminFunctions::save_menu_permission();
            echo $location;                  
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>