<?php
   // require("../models/mainfunctions.php");
   require("../models/promocodefunctions.php");

    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
   // print_r($task); die;
    switch($task)
    {
        case "create_promocode" :
            $response = PromocodeFunctions::create_promocode();
            echo $response;
            break;

        case "update_promocode" :
            $response = PromocodeFunctions::update_promocode();
            echo $response;
            break;    

        case "delete_promocode" :
            $response = PromocodeFunctions::delete_promocode();
            echo $response;
            break;  

        case "get_promocode" :
            $response = PromocodeFunctions::get_promocode();
            echo $response;
            break;  

        case "edit_promocode" :
            $response = PromocodeFunctions::edit_promocode();
            echo json_encode($response);
            break;

        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>