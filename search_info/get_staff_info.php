<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../../conf.ini';
require_once '../medoo/Medoo.php';

if(!isset($_SESSION["id"])) return;

if($_POST["get_staff"]){
        //取员工信息，并放在一个数组中；
        $conn = new mysqli($DBHOST, $DBUSER, $DBPWD , $DBNAME);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        } 
        $sql = "select wno, wname, wprivilege from wstaff;";
        $result = $conn->query($sql);

        //$staffs 保存员工信息
        $staffs = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $staff = array();
                foreach($row as $key => $value){
                    array_push($staff,$value);
                    #echo $value;
                }
               
                array_push($staffs, $staff);
                // array_push($staffs, $row);
                // echo $row['wname'] . "\n";
                #$staffs=$staffs . 
            }
        }

        //将 $staffs数组返回给客户端
        echo json_encode($staffs);
}
return;
?>