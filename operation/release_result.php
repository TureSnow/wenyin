<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once("../../conf.ini");
require_once '../medoo/Medoo.php';

$conn = new mysqli($DBHOST, $DBUSER, $DBPWD , $DBNAME);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
    return;
} 

    //用session保护
if(!isset($_SESSION["id"]))
    return ;

//只有管理员可做此操作，权限为1可以看到，但无法操作
if((int)($_SESSION["privilege"])>0)
    return ;

//将选班结果发布, 输出到"result_table.php"文件中
$file=fopen("result_table.php","w") or exit("Unable to open file!");

//黄力写的奇葩sql语句
$get_arrange_staff_1= "SELECT wstaff.wname from wstaff, arrange_shift  where arrange_shift.sno = ";
$get_arrange_staff_2= " and wstaff.wno = arrange_shift.wno;";


//下面是输出表格
fwrite($file,"<tr><th>第一班</th>");
for($i=1;$i<=28;$i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;
    //echo $sql;

    $result = $conn->query($sql);
    fwrite($file,"<td>");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fwrite($file,"<li>".$row['wname']."</li>");
        }
    }   
    fwrite($file,"<br></td>");
};

fwrite($file,"</tr><tr><th>第二班</th>");

for($i=2;$i<=28;$i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;

    $result = $conn->query($sql);
    fwrite($file,"<td>");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fwrite($file,"<li>".$row['wname']."</li>");
        }
    }   
    fwrite($file,"<br></td>");

};
fwrite($file,"</tr><tr><th>第三班</th>");

for($i=3;$i<=28;$i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;

    $result = $conn->query($sql);
    fwrite($file,"<td>");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fwrite($file,"<li>".$row['wname']."</li>");
        }
    }   
    fwrite($file,"<br></td>");

};

fwrite($file,"</tr><tr><th>第四班</th>");

for($i=4;$i<=28;$i+=4)
{
    $sql= $get_arrange_staff_1. $i . $get_arrange_staff_2;

    $result = $conn->query($sql);
    fwrite($file,"<td>");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fwrite($file,"<li>".$row['wname']."</li>");
        }
    }   
    fwrite($file,"<br></td>");

};
fwrite($file,"</tr>");

fclose($file);
$conn->close();
?>
<?php require_once 'release_result_2.php'; ?> 