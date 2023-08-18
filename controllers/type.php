<?php
    require("../models/typefunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "get_category1" :
            $response = TypeFunctions::get_users();
            echo $response;
            
            break;

        case "get_category1" :
            $response = TypeFunctions::get_users();
            echo json_encode($response);
            
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>