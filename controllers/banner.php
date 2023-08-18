<?php
   // require("../models/mainfunctions.php");
   require("../models/bannerfunctions.php");

    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
   // print_r($task); die;
    switch($task)
    {
        case "create_banner" :
            $response = BannerFunctions::create_banner();
            echo $response;
            break;

        case "update_banner" :
            $response = BannerFunctions::update_banner();
            echo $response;
            break;    

        case "delete_banner" :
            $response = BannerFunctions::delete_banner();
            echo $response;
            break;  

        case "get_banner" :
            $response = BannerFunctions::get_banner();
            echo $response;
            break;  

        case "edit_banner" :
            $response = BannerFunctions::edit_banner();
            echo json_encode($response);
            break;

        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>