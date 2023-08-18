<?php
include_once("generalfunctions.php");

class ProductFunctions {

    static function get_all_category1() {
        $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());

        $result = mysqli_query($con, "SELECT * FROM `ecom_level1_category` WHERE `is_deleted`='0' order by `l1_category`") or die(mysqli_error($con));
        $row = [];
        while ($fetch = mysqli_fetch_assoc($result)) {
            $row[] = $fetch;
        }
        mysqli_close($con);
        return $row;
    }

    static function delete_category5()
    {
        $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
        $id = $_POST['id'];
        $result = mysqli_query($con, "UPDATE `ecom_level5_category` SET `is_deleted`= '1' WHERE `id`='$id'") or die(mysqli_error($con));
        if ($result) {
            $data = json_encode([
                "status" => '1',
                "message" => "Category5 delete successfully."
            ]);
        } else {
            $data = json_encode([
                "status" => '0',
                "message" => "Something went wrong."
            ]);
        }
        mysqli_close($con);
        return $data;
    }
}
