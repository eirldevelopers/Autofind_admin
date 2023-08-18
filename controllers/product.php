<?php
    require("../models/productfunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "change_category1_status" :
            $response = ProductFunctions::change_category1_status();
            echo $response;

            break;
            
        case "create_category1" :
            $response = ProductFunctions::create_category1();
            echo $response;
            
            break;
            
        case "edit_category1" :
            $response = ProductFunctions::edit_category1();
            echo json_encode($response);
            
            break;

        case "get_all_category1" :
            $response = ProductFunctions::get_all_category1();
            echo json_encode($response);
            
            break;

        case "load_categories_l2" :
            $response = ProductFunctions::load_categories_l2();
            echo json_encode($response);
            
            break;

        case "load_unit_value" :
            $response = ProductFunctions::load_unit_value();
            echo json_encode($response);
            
            break;

        case "create_product" :
                $response = ProductFunctions::create_product();
                echo $response;
                
                break;

        case "change_product_status" :
                $response = ProductFunctions::change_product_status();
                echo $response;
                
                break;

        case "edit_product" :
            $response = ProductFunctions::edit_product();
            echo $response;
            
            break;

        case "update_product" :
            $response = ProductFunctions::update_product();
            echo $response;
            
            break;

        case "delete_product" :
            $response = ProductFunctions::delete_product();
            echo $response;
            
            break;

        case "get_all_category2" :
            $response = ProductFunctions::get_all_category2();
            echo json_encode($response);
            
            break;
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>