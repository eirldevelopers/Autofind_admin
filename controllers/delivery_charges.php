<?php
    require("../models/deliverychargesfunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "edit_delivery_charges" :
            $response = DeliveryChargesFunctions::edit_delivery_charges();
            echo json_encode($response);
            break;
            
          case "update_delivery_charges" :
            $response = DeliveryChargesFunctions::update_delivery_charges();
            echo $response;
            break;

        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>