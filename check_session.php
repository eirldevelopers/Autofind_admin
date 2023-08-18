<?php
    session_start();
    
    if(isset($_SESSION['tns_user']))
    {
        if($_SESSION['locked'] == 0)
        {
            $status = 1;
        }
        else
        {
            $status = -1;
        }
    }
    else
    {
        $status = 0;
    }
    
    echo json_encode(array("status"=>$status));
?>