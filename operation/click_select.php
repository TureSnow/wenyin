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
        $shift_id = $_POST["shift_id"];
        $flag = $_POST["flag"];
       
        echo $shift_id;
        if($flag=="1"){
            $database->insert('select_shift', [
                'wno' => $_SESSION["id"],
                'sno' => $shift_id,
            ]);
           
        }else{
            $database->delete("select_shift", [
                "wno" => $_SESSION["id"],
                'sno' => $shift_id,
        ]);
       }
    }
    return;
   
?>