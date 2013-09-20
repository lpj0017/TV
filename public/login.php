<?php
    header("Content-type: text/html; charset=utf8");
    require_once dirname(__FILE__).'/'.'../Database.php';
    require_once dirname(__FILE__).'/'.'../app/utils.php';
    
    // Detect GUID header, is not set, generate a GUID header
    $headers = apache_request_headers();
    $db = Database::getInstance();
    $profile = array();
    if (!isset($headers["GUID"]))
    {
        $guid = guid();
        header("GUID: ".$guid);
        $profile["GUID"] = $guid;
        $profile["first_use"] = date("Y/m/d H:i:s");
    }
    else
    {
        $guid = $headers["GUID"];
        $profile = $db->getProfile($guid);
        if ($profile == false)
            return;
    }
    
    @$ua = $headers["UA"];  
    $date = date("Y/m/d H:i:s");
    $profile["LastLogin"] = $date;
    if ($ua != null)
        $profile["UA"] = $ua;
    $db->storeProfile($profile);
?>
