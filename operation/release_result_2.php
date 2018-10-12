<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once("../../conf.ini");
require_once '../medoo/Medoo.php';
use Medoo\Medoo;

if(!isset($_SESSION["id"])) return;
//只有管理员可做此操作，权限为1可以看到，但无法操作
if((int)($_SESSION["privilege"])>0)
    return ;

$database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => $DBNAME,
        'server' =>  $DBHOST,
        'username' => $DBUSER,
        'password' => $DBPWD,
    ]);

    //所有排班的人-->字典
    $results = $database->select("arrange_shift", ["sno","wno"]);
    $arranged = array();
    foreach ($results as $v) {
        if(!isset( $arranged[$v["sno"]]))
                $arranged[$v["sno"]] =  array();
        array_push($arranged[$v["sno"]], $v['wno']);
    }
   //定制排班的两个参数
    $results = $database->select("custom", ["weeks","shift_per_day"]);
    $weeks = $results[0]["weeks"];
    $shift_per_day = $results[0]["shift_per_day"];

    //周的名字
    $weeksname = array();
    $results = $database->select("weekinfo", ["weekno","weekname"]);
    foreach($results as $v){
        $weeksname[$v["weekno"]] = $v["weekname"];
    }

    //映射字典
    $results = $database->select("wstaff", ["wno","wname"]);
    $dics = array();
    foreach($results as $v)
        $dics[$v["wno"]] = $v["wname"];
    

    //将选班结果发布, 输出到"result_table.csv"文件中
    $file=fopen("result_table.csv","w") or exit("Unable to open file!");
    fwrite($file,chr(0xEF).chr(0xBB).chr(0xBF));

    for($i=0; $i<$weeks; $i++){
        $tem = array($weeksname[($i+1).""],'周一','周二','周三','周四','周五','周六','周日');
        for($j=$i*$shift_per_day*7; $j <($i+1)*$shift_per_day*7; $j++){
            if($j%7==0){
                fputcsv($file,$tem);
                $tem = array("第".(($j-$i*$shift_per_day*7)/7+1)."班");
            }
            $a="";
            foreach($arranged[$j] as $x)
               if($x!="")
                  $a = $a.$dics[$x]."\n";
            array_push($tem, $a);
        }
        fputcsv($file,$tem);

    }
    fclose($file);
?>