<?php
    include_once("generalfunctions.php");
    
    class AdminFunctions
    {
        static function get_users($uid=0)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "select * from admin_users " . ($uid > 0 ? " where id=" . $uid : "") . " order by user_name");
            
            mysqli_close($con);
            return $result;
        }

       
        
        static function change_user_status()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $uid = mysqli_real_escape_string($con, intval($_POST['uid']));
            
            $result = mysqli_query($con, "update admin_users set active=1-active where id=" . $uid);
            
            if($result)
            {
                $user = mysqli_query($con, "select * from admin_users where id=" . $uid);
                $user = mysqli_fetch_assoc($user);
                
                $data = json_encode(array("success"=>YES, "msg"=>"User account " . ($user['active'] == YES ? "activated" : "deactivated") . " successfully."));
            }
            else
            {
                $data = json_encode(array("success"=>NO, "msg"=>"Some error occurred!!!\nPlease try again."));
            }
            
            mysqli_close($con);
            return $data;
        }
        
        static function reset_user_password()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $uid = mysqli_real_escape_string($con, intval($_POST['uid']));
            
            $result = mysqli_query($con, "update admin_users set password='" . md5("12345") . "' where id=" . $uid);
            
            if($result)
            {
                $data = json_encode(array("success"=>YES, "msg"=>"User password reset successfully."));
            }
            else
            {
                $data = json_encode(array("success"=>NO, "msg"=>"Some error occurred!!!\nPlease try again."));
            }
            
            mysqli_close($con);
            return $data;
        }
        
        static function create_user_login()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $user_name = trim(ucwords($_POST['user_name']));
            $mobile_no = trim($_POST['mobile_no']);
            $role = intval($_POST['role']);
            
            $result = mysqli_query($con, "select * from admin_users where mobile_no='" . $mobile_no . "'");
            
            if(mysqli_num_rows($result) > 0)
            {
                $data = json_encode(array("success"=>NO, "msg"=>"Duplicate Mobile No.!!!"));
            }
            else
            {
                $query = "insert into admin_users (user_name, mobile_no, role, active, registered_on) values ('" . $user_name . "', '" . $mobile_no . "', " . $role . ", " . YES . ", '" . date("Y-m-d") . "')";
                
                if(mysqli_query($con, $query))
                {
                    $uid = intval(mysqli_insert_id($con));
                    
                    $username = "TNS-" . $uid;
                    $password = "12345";
                    
                    mysqli_query($con, "update admin_users set username='" . $username . "', password='" . md5($password) . "' where id=" . $uid);
                    
                    $data = json_encode(array("success"=>YES, "msg"=>"User added successfully."));
                }
                else
                {
                    $data = json_encode(array("success"=>NO, "msg"=>"Some error occurred!!!\nPlease try again."));
                }
            }
            
            mysqli_close($con);
            return $data;
        }
        
        static function get_all_menus($level = "")
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $menus = array();
            
            $sql = mysqli_query($con, "select * from menus where pid=0 " . ($level != "" ? " and level like '%" . $level . "%'" : "") . " order by m_order");
            while($row = mysqli_fetch_assoc($sql))
            {
                $parent_id = $row['id'];
                
                $menus[$parent_id] = new stdClass();
                $menus[$parent_id]->id = $parent_id;
                $menus[$parent_id]->menu = $row["menu"];
                $menus[$parent_id]->link = $row["link"];
                $menus[$parent_id]->pid = $row["pid"];
                $menus[$parent_id]->m_order = $row["m_order"];
                $menus[$parent_id]->level = $row["level"];
                $menus[$parent_id]->children = array();
                
                $children = array();
                
                $sql1 = mysqli_query($con, "select * from menus where pid=" . $parent_id . ($level != "" ? " and level like '%" . $level . "%'" : "") . " order by m_order");
                while($row1 = mysqli_fetch_assoc($sql1))
                {
                    $menu_id = $row1["id"];
                    
                    $children[$menu_id] = new stdClass();
                    $children[$menu_id]->id = $menu_id;
                    $children[$menu_id]->menu = $row1["menu"];
                    $children[$menu_id]->link = $row1["link"];
                    $children[$menu_id]->pid = $row1["pid"];
                    $children[$menu_id]->m_order = $row1["m_order"];
                    $children[$menu_id]->level = $row1["level"]; 
                }
                
                $menus[$parent_id]->children = $children;
            }

            mysqli_close($con);
            return $menus;
        }
        
        static function get_menu_permissions($uid)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "select group_concat(mid) mids from mper where uid=" . $uid);
            
            $menus_ids = array();
            
            while($row = mysqli_fetch_assoc($result))
            {
                $menus_ids = explode(",", $row['mids']);
            }
            
            mysqli_close($con);
            return $menus_ids;
        } 
                
        static function save_menu_permission()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $menus = (isset($_POST['menuid']) ? $_POST['menuid'] : array());
            $uid = intval($_POST['uid']);
            $user_type = $_POST['utype'];
            mysqli_query($con, "delete from mper where uid=" . $uid);
            
            if(count($menus) > 0)
            {
                foreach($menus as $mid)
                {
                    mysqli_query($con, "insert into mper (uid, mid,user_type) values (" . $uid . ", " . $mid . ", " . $user_type . ")");
                }
            }
            
            session_start();
            
            $_SESSION['msg_type'] = MSG_SUCCESS;
            $_SESSION['msg'] = "Permissions Saved Successfully.";
            
            mysqli_close($con);
        }
    }
?>