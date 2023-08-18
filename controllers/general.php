<?php
    require("../models/generalfunctions.php");
    
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    
    switch($task)
    {
        case "user_login" :
            $success = GeneralFunctions::user_login();
            
            if($success)
            { header("Location: ../views/dashboard.php"); }
            else
            { header("Location: ../index.php"); }
            
            break;
            
        case "change_password" :
            GeneralFunctions::change_password();
            header("Location: ../views/changepassword.php");
            
            break;
            
        /*case "send_new_password" :
            $response = GeneralFunctions::send_new_password();
            echo $response;
            
            break;*/
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>