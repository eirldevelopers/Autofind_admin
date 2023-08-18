<?php
    include_once("generalfunctions.php");
    
    class TypeFunctions
    {
        static function get_users($uid=0) {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `users` WHERE `active`='1' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }

         static function get_sellers($uid=0)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "select * from sellers " . ($uid > 0 ? " where id=" . $uid : "") . " order by seller_name");
            
            mysqli_close($con);
            return $result;
        }

        static function get_candidates($uid=0) {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "SELECT * FROM `application_forms` WHERE `is_deleted`='0' order by `id` DESC")or die(mysqli_error($con));
            mysqli_close($con);
            return $result;
        }


    }
