<?php
    header("Content-type: text/html; charset=utf8");
    require_once dirname(__FILE__).'/'.'../../Database.php';
    require_once dirname(__FILE__).'/'.'../../CacheLock.php';
    require_once dirname(__FILE__).'/'.'./utils.php';
    
    if(isset($_GET["channel"]) && isset($_GET["day"]))
    {
        $id = $_GET["channel"];
        $day = $_GET["day"];
        //echo "You select channel: ".$id." on day ".$day."<br />";
    }
    else
    {
        //echo "You should select channel and day!!"."<br />";
        $result["result"] = array();
        echo json_encode($result);
        return;
    }
    $needOnPlaying = false;
    if (isset($_GET["onplaying"]))
        $needOnPlaying = true;
    $db = Database::getInstance();
    $channel = $db->getChannelById($id);
    
    // 该节目id不存在
    if ($channel == false)
    {
        $result["result"] = array();
        echo json_encode($result);
        return;
    }
    
    foreach ($channel["days"][$day] as $program)
    {
        //echo $program["time"].": ".$program["title"]."<br />";
        $array[] = array("time" => $program["time"], "title" => $program["title"]);
    }
    
    $result["result"] = $array;
    $result["id"] = $id;
    $result["day"] = $day;
    if ($needOnPlaying)
    {
        $program = getOnPlayingProgram($channel);
        $result["onplaying"] = $program;
    }
    
    echo json_encode($result);
//    var_dump($result);
    
    // 记录该channel被访问次数
    $lock = new CacheLock(__FILE__);
    $lock->lock();      // 读写文件需要保证原子性，否则会读到脏数据或写乱文件
    $visit_records = $db->getChannelVisitRecords();
    if ($visit_records == false)    // First use
    {
        $visit_records = array();
    }
    $date = date("Y/m/d");
    if (!isset($visit_records["$date"][$id]["VisitTimes"]))
        $visit_records["$date"][$id]["VisitTimes"] = 1;
    else
        $visit_records["$date"][$id]["VisitTimes"] += 1;
    $db->storeChannelVisitRecords($visit_records);
    $lock->unlock();
//    var_dump($visit_records);
    
    // --------------------------- Functions --------------------------------  
    function getNameOfDay($day)
    {   
        $weekarray = array("一","二","三","四","五","六","日");  
        return "星期".$weekarray[intval($day) - 1]; 
    }
    
    function dump($channels)
    {
        foreach ($channels as $channel => $list)
        {
            echo $channel.": <br />";
            foreach ($list as $program)
            {
                echo $program["time"].": ".$program["title"]."<br />";
            }
        }
    }
    
?>

