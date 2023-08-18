<?php
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
<div id="page-wrapper" class="gray-bg">
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0;background: #f3f3f4 !important;">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="../lock_access.php?rurl=<?php echo base64_encode($current_url); ?>" style="color:#a7b1c2;"><i class="fa fa-lock"></i> Lock</a>
            </li>
            <li>
                <a href="logout.php" style="color:#a7b1c2;"><i class="fa fa-sign-out"></i> Log out</a>
            </li>
        </ul>

    </nav>
</div>