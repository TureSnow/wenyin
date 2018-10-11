<?php
    session_start();
    header("Content-type: text/html; charset=utf-8");
    require_once("../../conf.ini");
    require_once '../medoo/Medoo.php';
    use Medoo\Medoo;


    if(!isset($_SESSION["id"])) return;
    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => $DBNAME,
        'server' =>  $DBHOST,
        'username' => $DBUSER,
        'password' => $DBPWD,
    ]);

    //删除上次提交的选班数据，插入新选班数据
    if(isset($_POST)){
        $database->delete("select_shift", [
                "wno" => $_SESSION["id"]
        ]);

        $shifts=array();
        for($i=1; $i<=28; $i++){
            $str="shift";
            $str=$str.(string)$i;
            if(isset($_POST[$str])){
                    $database->insert('select_shift', [
                        'wno' => $_SESSION["id"],
                        'sno' => $i ,
                    ]);
            }
        }
    }
   
?>