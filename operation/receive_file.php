<?php
    session_start();
    header("Content-type: text/html; charset=utf-8");
    require_once("../../conf.ini");
    require_once '../medoo/Medoo.php';
    use Medoo\Medoo;

    if(!isset($_SESSION["id"])) return;
    if((int)($_SESSION["privilege"])>0) return ;


    $target_path  = "./2.xlsx"; //接收文件目录
	//$target_path = $target_path . basename( $_FILES['confile']['name']);
	if(move_uploaded_file($_FILES['confile']['tmp_name'], $target_path)) {
	   echo "The file ".  basename( $_FILES['confile']['name']). " has been uploaded";
	}else{
	   echo "There was an error uploading the file, please try again!" . $_FILES['confile']['error'];
	}
    //运行python脚本
    $url='http://0.0.0.0:8087/renew_staff';
    $result = file_get_contents($url, false, $context);
    echo $result;

?>