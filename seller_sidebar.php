<?php
    $page = basename($_SERVER['PHP_SELF'], ".php");
    
    $current_url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    {
        $current_url = "https://" . $current_url;
    }
    else
    {
        $current_url = "http://" . $current_url;
    }
?>
<script>
    function activate_menu(mid)
    {
        document.getElementById("menu"+mid).classList.add("active");
    }
</script>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <i class="fa fa-user fa-5x" style="color:#fff;"></i>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold"><?php echo $_SESSION['username']; ?></span>
                        <span class="text-muted text-xs block">Menu <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="changepassword.php">Change Password</a></li>
                        <li><a class="dropdown-item" href="../lock_access.php?rurl=<?php echo base64_encode($current_url); ?>">Lock A/c</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    Admin
                </div>
            </li>
            <li class="<?php echo ($page == "dashboard" ? "active" : ""); ?>">
                <a href="dashboard.php" title="Dashboard"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <?php
                $menus = GeneralFunctions::get_menus();
                
                while($menu = mysqli_fetch_assoc($menus))
                {
                    $childmenus = GeneralFunctions::get_menus($menu['id']);
                    $childcount = mysqli_num_rows($childmenus);
                    
                    if($childcount > 0)
                    {
                        ?>
                        <li id="menu<?php echo $menu['id']; ?>">
                            <a href="<?php echo $menu['link']; ?>" title="<?php echo $menu['menu']; ?>"><i class="fa fa fa-link"></i> <span class="nav-label"><?php echo $menu['menu']; ?></span><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse" aria-expanded="false">
                                <?php
                                    while($childmenu = mysqli_fetch_assoc($childmenus))
                                    {
                                        ?><li class="<?php echo ($page == $childmenu['link'] ? "active" : ""); ?>"><a href="<?php echo $childmenu['link']; ?>.php" title="<?php echo $childmenu['menu']; ?>"><i class="icon fa fa-circle-o"></i> <?php echo $childmenu['menu']; ?></a></li><?php
                                        
                                        if($page == $childmenu['link'])
                                        {
                                            echo "<script type='text/javascript'>activate_menu(" . $menu['id'] . ")</script>";
                                        }
                                    }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <li class="<?php echo ($page == $menu['link'] ? "active" : ""); ?>">
                            <a href="<?php echo $menu['link']; ?>.php" title="<?php echo $menu['menu']; ?>"><i class="fa fa-link"></i> <span class="nav-label"><?php echo $menu['menu']; ?></span></a>
                        </li>
                        <?php
                    }
                }
            ?>
            
            <?php
                if($_SESSION['role'] == SUPERUSER)
                {
                    ?>
                    <li class="<?php echo ($page == "menus" ? "active" : ""); ?>">
                        <a href="menus.php" title="Menus"><i class="fa fa-link"></i> <span class="nav-label">Menus</span></a>
                    </li>
                    <?php
                }
                else if($_SESSION['role'] == ADMINISTRATOR)
                {
                    ?>
                    <li class="<?php echo ($page == "users" ? "active" : ""); ?>">
                        <a href="users.php" title="Users"><i class="fa fa-link"></i> <span class="nav-label">Users</span></a>
                    </li>
                    <?php
                }
                else if($_SESSION['role'] == ADMIN) {
                    ?>
                    <?php
                }
            ?>
        </ul>
    </div>
</nav>