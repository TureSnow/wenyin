<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once("../format/.conf.ini");
require_once '../medoo/Medoo.php';

$conn = new mysqli($DBHOST, $DBUSER, $DBPWD , $DBNAME);
// 检测连接

    //用session保护
if(!isset($_SESSION["id"]))
    return ;

//只有管理员可做此操作，权限为1可以看到，但无法操作
if((int)($_SESSION["privilege"])>0)
    return ;


if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
    return;
} 

//将选班结果发布, 输出到"result_table.csv"文件中
$file=fopen("result_table.csv","w") or exit("Unable to open file!");
fwrite($file,chr(0xEF).chr(0xBB).chr(0xBF));

//黄力写的奇葩sql语句
$get_arrange_staff_1= "SELECT wstaff.wname from wstaff, arrange_shift  where arrange_shift.sno = ";

$get_arrange_staff_2= " and wstaff.wno = arrange_shift.wno;";

fputcsv($file,array('排班表','周一','周二','周三','周四','周五','周六','周日'));   
//下面是输出表格
$shift_1=array('第一班');
for($i=1; $i<=28; $i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $staffs='';
        while ($row = $result->fetch_assoc()) {
            $staffs=$staffs . $row['wname'] . "\n";
        }
        array_push($shift_1, $staffs);
    } 
    else 
        array_push($shift_1, "");
};
fputcsv($file, $shift_1); 

$shift_2=array('第二班');
for($i=2; $i<=28; $i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $staffs='';
        while ($row = $result->fetch_assoc()) {
            $staffs=$staffs . $row['wname'] . "\n";
        }
        array_push($shift_2, $staffs);
    } 
    else 
        array_push($shift_2, "");

};
fputcsv($file, $shift_2); 
// fwrite($file,"</tr><tr><th>第三班</th>");

$shift_3=array('第三班');
for($i=3; $i<=28; $i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $staffs='';
        while ($row = $result->fetch_assoc()) {
            $staffs=$staffs . $row['wname'] . "\n";
            
        }
        array_push($shift_3, $staffs);
    }
    else 
       array_push($shift_3, "");

};
fputcsv($file, $shift_3); 

$shift_4=array('第四班');
for($i=4; $i<=28; $i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $staffs='';
        while ($row = $result->fetch_assoc()) 
            $staffs=$staffs . $row['wname'] . "\n";
        array_push($shift_4, $staffs);
    }
    else 
        array_push($shift_4, "");
};

fputcsv($file, $shift_4);
fclose($file);
$conn->close();
?>