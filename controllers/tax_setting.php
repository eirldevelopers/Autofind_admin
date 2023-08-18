<?php
    require("../models/taxsettingfunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "add_tax_setting" :
            $response = Taxsettingfunctions::create_promocode();
            echo $response;
            break;
        
        case "edit_tax_setting" :
            $response = Taxsettingfunctions::edit_taxsetting();
            echo json_encode($response);
            break;
            
          case "update_tax_rate" :
            $response = Taxsettingfunctions::update_taxsetting();
            echo $response;
            break;

        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>