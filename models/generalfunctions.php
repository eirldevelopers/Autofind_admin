<?php
    if(file_exists("config.php"))
    {
        include_once("config.php");
        include_once("constants.php");
    }
    else
    {
        include_once("../config.php");
        include_once("../constants.php");
    }
    $url = basename(PATH, ".php");
    class GeneralFunctions
    {
        static function check_session()
        {           
            @session_start();
            
            if(isset($_SESSION['tns_user']))
            {
                if($_SESSION['locked'] == 0)
                {
                    return true;
                }
                else
                {
                    $current_url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    
                    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    {
                        $current_url = "https://" . $current_url;
                    }
                    else
                    {
                        $current_url = "http://" . $current_url;
                    }
                    
                    header("Location: ../lockscreen.php?rurl=" . base64_encode($current_url));
                    exit;
                }
            }
            else
            {
                header("Location: ../index.php");
                exit;
            }
        }
        
        static function check_menu_permission($page)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $user_id = $_SESSION['tns_user'];
            
            if($user_id == SUPERUSER)
            {
                $pages = array("dashboard", "menus", "changepassword");
                
                if(in_array($page, $pages))
                {
                    return true;
                }
                else
                {
                    header("Location: ../index.php");
                    return;
                }
            }
            else if($user_id == ADMINISTRATOR)
            {
                $pages = array("dashboard", "users", "changepassword");
                
                if(in_array($page, $pages))
                {
                    return true;
                }
                else
                {
                    header("Location: ../index.php");
                    return;
                }
            }
            else
            {
                $result = mysqli_query($con, "select * from menus m inner join mper mp on m.id=mp.mid where mp.uid=" . $user_id . " and m.link='" . $page . "'");
                
                if(mysqli_num_rows($result) > 0)
                {
                    mysqli_close($con);
                    return true;
                }
                else
                {
                    $pages = array("dashboard", "changepassword");
                    
                    if(in_array($page, $pages))
                    {
                        return true;
                    }
                    else
                    {
                        header("Location: ../index.php");
                        return;
                    }
                }
            }
        }
        
        static function user_login()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $username = mysqli_real_escape_string($con, stripslashes($_POST['username']));
            $password = mysqli_real_escape_string($con, stripslashes($_POST['password']));
            
            $password = md5($password);
            
            session_start();
            
            $superuser_username = self::get_setting("superuser_username");
            $administrator_username = self::get_setting("administrator_username");
            $success = 0;
            
            if($username == $superuser_username)
            {
                $superuser_password = self::get_setting("superuser_password");
                
                if($password != $superuser_password)
                {
                    $_SESSION['login_error'] = "Your password is incorrect!!!";
                }
                else
                {
                    $success = 1;
                    
                    $_SESSION['tns_user'] = SUPERUSER;
                    $_SESSION['user_id'] = SUPERUSER;
                    $_SESSION['role'] = SUPERUSER;
                    $_SESSION['locked'] = 0;
                    $_SESSION['username'] = "Super User";
                    $_SESSION['registered_on'] = "2021-05-01";
                }
            }
            else if($username == $administrator_username)
            {
                $administrator_password = self::get_setting("administrator_password");
                
                if($password != $administrator_password)
                {
                    $_SESSION['login_error'] = "Your password is incorrect!!!";
                }
                else
                {
                    $success = 1;
                    $_SESSION['tns_user'] = ADMINISTRATOR;
                    $_SESSION['user_id'] = ADMINISTRATOR;
                    $_SESSION['role'] = ADMINISTRATOR;
                    $_SESSION['locked'] = 0;
                    $_SESSION['username'] = "Administrator";
                    $_SESSION['registered_on'] = "2021-05-01";
                }
            }
            else
            {
                /*===========================Admin User Login=============================*/
                $query_admin = "select * from admin_users where username='" . $username . "' and active=" . YES;
                $result_admin = mysqli_query($con, $query_admin);
                if(mysqli_num_rows($result_admin) > 0)
                {
                  $login_type='admin';  
                }
               /*===========================Seller User Login=============================*/
                $query_sellers = "select * from ecom_sellers where email_id='" . $username . "' and acnt_status=" . YES;
                $result_sellers = mysqli_query($con, $query_sellers);
                if(mysqli_num_rows($result_sellers) > 0)
                {
                   $login_type='seller';
                }

                if($login_type=='seller' || $login_type=='admin')
                {
                    if($login_type=='seller')
                    {   
                    $row = mysqli_fetch_assoc($result_sellers);
                // print_r($row['password']); 
                   // echo "<br>".$password;  die; 
                    }else{
                    $row = mysqli_fetch_assoc($result_admin);

                    }
                    if($row['password'] != $password)
                    {
                        $_SESSION['login_error'] = "Your password is incorrect!!!";
                    }
                    else
                    {
                        $success = 1;
                        
                        $_SESSION['tns_user'] = $row['id'];
                        $_SESSION['user_id'] = $row['id'];
                        if($login_type=='seller')
                        {
                         $_SESSION['role'] = '3';
                        }else{
                         $_SESSION['role'] = $row['role'];
                        }
                        $_SESSION['locked'] = 0;
                        $_SESSION['username'] = $row['user_name'];
                        $_SESSION['registered_on'] = $row['registered_on'];
                    }
                }
                else
                {
                    $_SESSION['login_error'] = "User does not exists!!!";
                }
            }
            
            mysqli_close($con);
            return $success;
        }
        
        static function logout()
        {
            session_start();
            session_unset();
            session_destroy();
            
            header("Location: ../index.php");
        }
        
        static function lock_access($rurl)
        {
            session_start();
            
            $_SESSION['locked'] = 1;
            
            header("Location: lockscreen.php?rurl=" . $rurl);
        }
        
        static function unlock_access()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $password = mysqli_real_escape_string($con, stripslashes($_POST['password']));
            $rurl = base64_decode($_POST['rurl']);
            
            session_start();
            
            // print_r($_SESSION);exit;
            if(isset($_SESSION['tns_user']))
            {
                $user_id = $_SESSION['user_id'];
                
                if($user_id == SUPERUSER)
                {
                    $saved_password = self::get_setting("superuser_password");
                }
                else if($user_id == ADMINISTRATOR)
                {
                    $saved_password = self::get_setting("administrator_password");
                }
                else
                {
                    $query = "select password from admin_users where id=" . $user_id . " and active=" . YES;
                    $result = mysqli_query($con, $query);
                    
                    $row = mysqli_fetch_assoc($result);
                    
                    $saved_password = $row['password'];
                    // print_r($saved_password);exit;
                }
                
                if($saved_password != md5($password))
                {
                    $_SESSION['login_error'] = "Your password is incorrect!!!";
                    
                    mysqli_close($con);
                    header("Location: lockscreen.php?rurl=" . base64_encode($rurl));
                }
                else
                {
                    $_SESSION['locked'] = 0;
                    
                    mysqli_close($con);
                    header("Location: " . $rurl);
                }
            }
            else
            {
                header("Location: index.php");
            }
        }
        
        static function switch_user_account()
        {
            session_start();
            
            session_unset();
            session_destroy();
            
            header("Location: index.php");
        }
        
        static function change_password()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            session_start();
            $user_id = intval($_SESSION['user_id']);
            
            $password = $_POST['password'];
            
            if($user_id == SUPERUSER)
            {
                $result = mysqli_query($con, "update settings set vstring='" . md5($password) . "' where skey='superuser_password'");
            }
            else if($user_id == ADMINISTRATOR)
            {
                $result = mysqli_query($con, "update settings set vstring='" . md5($password) . "' where skey='administrator_password'");
            }
            else
            {
                $result = mysqli_query($con, "update admin_users set password='" . md5($password) . "' where id=" . $user_id);
            }
            
            if($result)
            {
                $_SESSION['msg_type'] = MSG_SUCCESS;
                $_SESSION['msg'] = "Password changed successfully.";
            }
            else
            {
                $_SESSION['msg_type'] = MSG_ERROR;
                $_SESSION['msg'] = "Some error occurred!!!\nPlease try again.";
            }
            
            mysqli_close($con);
        }
        
        /*static function send_new_password()
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $mobile_no = $_POST['mobile_no'];
            
            $password = self::random_string(6);
            
            $result = mysqli_query($con, "update admin_users set password='" . md5($password) . "' where mobile_no='" . $mobile_no . "' and active=" . YES);
            
            if(mysqli_affected_rows($con))
            {
                if($result)
                {
                    $msgData = array(
                        'sMsg'    => "Your new password is " . $password . "\n " . SITE_NAME,
                        'sMob'    => $mobile_no
                    );

                    $status = GeneralFunctions::send_message($msgData);
                    
                    if($status)
                    {
                        $data = json_encode(array("success"=>1, "msg"=>"Your new password has been messaged. Don't forget to change your password after login."));
                    }
                    else
                    {
                        $data = json_encode(array("success"=>0, "msg"=>"Message sending failed! Please try again."));
                    }
                }
                else
                {
                    $data = json_encode(array("success"=>0, "msg"=>"Unable to change password! Please try again."));
                }
            }
            else
            {
                $data = json_encode(array("success"=>0, "msg"=>"Account does not exists! Please check the number."));
            }
            
            mysqli_close($con);
            return $data;
        }*/
        
        static function get_menus($pid = 0)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $user_id = $_SESSION['user_id'];
            
            $query = "select m.id, m.menu, m.link from menus m inner join mper mp on m.id=mp.mid where mp.uid=" . $user_id . " and m.pid=" . $pid . " order by m.m_order";
            
            $menus = mysqli_query($con, $query);
            
            mysqli_close($con);
            return $menus;
        }
        
        static function get_setting($skey)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $result = mysqli_query($con, "select * from settings where skey='" . $skey . "'");
            $result = mysqli_fetch_assoc($result);
            
            if($result['isnum'] == 1)
            {
                mysqli_close($con);
                return $result['vnumeric'];
            }
            else if($result['isnum'] == 0)
            {
                mysqli_close($con);
                return $result['vstring'];
            }
            else
            {
                mysqli_close($con);
                return;
            }
        }
        
        static function send_message($msgData)
        {
            $mobileNumber = $msgData['sMob'];
            $admin_message = $msgData['sMsg'];
            $message = urlencode($admin_message);
            
            $route = "4";
            $postData = array(
                'authkey' => MSG_AUTH_KEY,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => MSG_SENDER_ID,
                'route' => $route,
                'unicode' => 1,
                'country' => 91
            );

            //API URL
            $url="https://control.msg91.com/api/sendhttp.php";

            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
            ));

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $output = curl_exec($ch);
            if(curl_errno($ch))
            {
                //echo 'error:' . curl_error($ch);
                $status = 0;
            }
            else
            {
                $status = 1;
            }

            curl_close($ch);
            return $status;
        }
        
        static function random_string($length)
        {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for($i = 0; $i < $length; $i++)
            {
                $key .= $keys[array_rand($keys)];
            }

            return $key;
        }
        
        static function get_dropdown_data($columns, $table, $condition, $order_by, $json, $json_parameters)
        {
            $con = mysqli_connect(SERVER, DB_USERNAME, DB_PASSWORD, DATABASE) or die(mysqli_connect_error());
            
            $col = implode(",", $columns);
            $order_by_col = implode(",", $order_by);
            
            $where_condition = "";
            if(count($condition) > 1)
            {
                $where_condition = implode($condition[0], $condition[1]);
            }
            
            $result = mysqli_query($con, "select " . $col . " from " . $table . ($where_condition != "" ? " where " . $where_condition : "") . " order by " . $order_by_col);
            
            if($json == 1)
            {
                $data = "<option value=''> - " . $json_parameters['placeholder'] . " - </option>";
                if(mysqli_num_rows($result))
                {
                    foreach($result as $row)
                    {
                        $data .= "<option value='" . $row[$json_parameters['key']] . "'" .(in_array($row[$json_parameters['key']], $json_parameters['selected']) ? "selected" : "") . ">" . $row[$json_parameters['value']] . "</option>";
                    }
                }
                
                mysqli_close($con);
                return json_encode($data);
            }
            else
            {
                mysqli_close($con);
                return $result;
            }
        }
    }
?>