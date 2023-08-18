<?php
    ini_set("display_errors", true);  // Change it to false when live
    date_default_timezone_set('Asia/Calcutta');

    define("SERVER", "localhost");
    define("DB_USERNAME", "root");
    define("DB_PASSWORD", "");
    define("DATABASE", "auto_find");

    // define("SERVER", "localhost");
    // define("DB_USERNAME", "ai7inxll_atul_thenirmanstore");
    // define("DB_PASSWORD", "H1_NAD.~7^[4");
    // define("DATABASE", "ai7inxll_thenirmanstore");
    
    // User Role
    define("SUPERUSER", -2);
    define("ADMINISTRATOR", -1);
    define("ADMIN", 1);
    define("OPERATOR", 2);
    define("TARGET_DIR", "../../uploads/");

    define("SITE_NAME", "Autofind");
    define("SERVER_BASE_URL", "http://localhost/autofind/admin/");
    define("WEBSITE_BASE_URL", "http://localhost/autofind/");
    //define("IMAGE_URL", "http://localhost/thenirmanstore/");
    define("IMAGE_URL", "https://autofind.com/");
    
    define("PRODUCT_URL", "http://localhost/Autofind/images/");
    
    define("PATH", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

     //define("TARGET_DIR", $_SERVER['DOCUMENT_ROOT']."/thenirmanstore/");
    // define("LOCAL_URL", "http://manage.thenirmanstore.com/");
    // define("IMAGE_URL", "http://manage.thenirmanstore.com/assets/img/");
    // define("SERVER_BASE_URL", "http://manage.thenirmanstore.com/"); define("WEBSITE_BASE_URL", "http://thenirmanstore.com");
    
    // Message Type
    define("MSG_ERROR", "1");
    define("MSG_WARNING", "2");
    define("MSG_SUCCESS", "3");
    define("MSG_INFORMATION", "4");
?>