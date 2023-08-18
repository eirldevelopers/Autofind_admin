<?php
    require("../models/superuserfunctions.php");
    
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    
    switch($task)
    {
        case "save_menu" :
            $response = SuperuserFunctions::save_menu();
            echo $response;
            
            break;
            
        case "delete_menu" :
            $response = SuperuserFunctions::delete_menu();
            echo $response;
            
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>