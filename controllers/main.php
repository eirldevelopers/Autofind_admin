<?php
    require("../models/mainfunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "change_category1_status" :
            $response = ProductFunctions::change_category1_status();
            echo $response;

            break;
            
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>