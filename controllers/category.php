<?php
    require("../models/categoryfunctions.php");
    $task = (isset($_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b']) ? $_POST['c1e7c5cfd3d06ee8ef28b5c807d50f3b'] : $_GET['c1e7c5cfd3d06ee8ef28b5c807d50f3b']);
    $task = base64_decode($task);
    switch($task)
    {
        case "change_category1_status" :
            $response = CategoryFunctions::change_category1_status();
            echo $response;
            
            break;
            
        case "create_category1" :
            $response = CategoryFunctions::create_category1();
            echo $response;
            
            break;
            
        case "edit_category1" :
            $response = CategoryFunctions::edit_category1();
            echo json_encode($response);
            
            break;

        case "get_all_category1" :
            $response = CategoryFunctions::get_all_category1();
            echo json_encode($response);
            
            break;

        case "update_category1" :
            $response = CategoryFunctions::update_category1();
            echo $response;
            
            break;

        case "delete_category1" :
            $response = CategoryFunctions::delete_category1();
            echo $response;
            
            break;

        case "create_category2" :
            $response = CategoryFunctions::create_category2();
            echo $response;
            
            break;

        case "edit_category2" :
            $response = CategoryFunctions::edit_category2();
            echo json_encode($response);
            
            break;


        case "update_category2" :
            $response = CategoryFunctions::update_category2();
            echo $response;
            
            break;

        case "change_category2_status" :
            $response = CategoryFunctions::change_category2_status();
            echo $response;
            
            break;

        case "delete_category2" :
            $response = CategoryFunctions::delete_category2();
            echo $response;
            
            break;

        case "get_all_category2" :
            $response = CategoryFunctions::get_all_category2();
            echo json_encode($response);
            
            break;

        case "create_category3" :
            $response = CategoryFunctions::create_category3();
            echo $response;
            
            break;

        case "edit_category3" :
            $response = CategoryFunctions::edit_category3();
            echo json_encode($response);
            
            break;


        case "update_category3" :
            $response = CategoryFunctions::update_category3();
            echo $response;
            
            break;

        case "change_category3_status" :
            $response = CategoryFunctions::change_category3_status();
            echo $response;
            
            break;

        case "delete_category3" :
            $response = CategoryFunctions::delete_category3();
            echo $response;
            
            break;

        case "get_all_category3" :
            $response = CategoryFunctions::get_all_category3();
            echo json_encode($response);
            
            break;

        case "create_category4" :
            $response = CategoryFunctions::create_category4();
            echo $response;
            
            break;

        case "edit_category4" :
            $response = CategoryFunctions::edit_category4();
            echo json_encode($response);
            
            break;


        case "update_category4" :
            $response = CategoryFunctions::update_category4();
            echo $response;
            
            break;

        case "change_category4_status" :
            $response = CategoryFunctions::change_category4_status();
            echo $response;
            
            break;

        case "delete_category4" :
            $response = CategoryFunctions::delete_category4();
            echo $response;
            
            break;

        case "get_all_category4" :
            $response = CategoryFunctions::get_all_category4();
            echo json_encode($response);
            
            break;

        case "create_category5" :
            $response = CategoryFunctions::create_category5();
            echo $response;
            
            break;

        case "edit_category5" :
            $response = CategoryFunctions::edit_category5();
            echo json_encode($response);
            
            break;


        case "update_category5" :
            $response = CategoryFunctions::update_category5();
            echo $response;
            
            break;

        case "change_category5_status" :
            $response = CategoryFunctions::change_category5_status();
            echo $response;
            
            break;

        case "delete_category5" :
            $response = CategoryFunctions::delete_category5();
            echo $response;
            
            break;
        
        default :
            header("Location: ../views/dashboard.php");
            break;
    }
?>