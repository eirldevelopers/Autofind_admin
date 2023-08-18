<?php
    include_once("generalfunctions.php");
    
    class SuperuserFunctions
    {
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
        
        static function save_menu()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            session_start();
            
            $level = "";
            $mid = intval($_POST["mid"]);
            
            if($mid == 0)
            {
                $query = "insert into menus (menu, link, pid, m_order, level) values ('" . trim($_POST['menu']) . "', '" . trim($_POST['link']) . "', " . intval($_POST['parent_id']) . ", " . floatval($_POST['m_order']) . ", '" . $level . "')";
                $msg = "Menu added successfully.";
            }
            else
            {
                $query = "update menus set menu='" . trim($_POST['menu']) . "', link='" . trim($_POST['link']) . "', pid=" . intval($_POST['parent_id']) . ", m_order=" . floatval($_POST['m_order']) . ", level='" . $level . "' where id=" . $mid;
                $msg = "Menu updated successfully.";
            }            
            
            if(mysqli_query($con, $query))
            {
                if($mid == 0) { $mid = intval(mysqli_insert_id($con)); }
                self::reorder_menu();
                
                mysqli_close($con);
                return json_encode(array("msg"=>$msg, "success"=>YES));
            }
            else
            {
                mysqli_close($con);
                return json_encode(array("success"=>NO, "msg"=>"Some error occurred!!!\nPlease try again."));
            }
        }
        
        static function delete_menu()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            session_start();
            
            $mid = intval($_POST['mid']);
            $pid = intval($_POST['pid']);
            
            if($pid == 0)
            {
                $query = "delete from menus where id=" . $mid . " or pid=" . $mid;
            }
            else
            {
                $query = "delete from menus where id=" . $mid;
            }
            
            if(mysqli_query($con, $query))
            {
                self::reorder_menu();
                
                mysqli_close($con);
                return json_encode(array("msg"=>"Menu deleted successfully.", "success"=>YES));
            }
            else
            {
                mysqli_close($con);
                return json_encode(array("success"=>NO, "msg"=>"Some error occurred!!!\nPlease try again."));
            }
        }
        
        static function reorder_menu()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $parent_order = 0;
            
            $sql = mysqli_query($con, "select id from menus where pid=0 order by m_order");
            while($row = mysqli_fetch_assoc($sql))
            {
                $parent_order++;
                $child_order = 0;
                
                mysqli_query($con ,"update menus set m_order=" . $parent_order . " where id=" . $row['id']);
                
                $sql1 = mysqli_query($con, "select id from menus where pid=" . $row['id'] . " order by m_order");
                while($row1 = mysqli_fetch_assoc($sql1))
                {
                    $child_order++;
                    
                    mysqli_query($con ,"update menus set m_order=" . $child_order . " where id=" . $row1['id']);
                }
            }
            
            mysqli_close($con);
        }
    }
?>