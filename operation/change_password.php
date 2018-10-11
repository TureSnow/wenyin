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
    
    if(isset($_POST["origin_password"])){
        //密码转化为密文比较
        $password=hash('sha256', $_POST["origin_password"] );
        $data=$database->select("wstaff", "wpassword", ["wno" => $_SESSION["id"]]);

        //如果密码输入正确，更新密码
        if($data[0]==$password){
            $new_password= hash('sha256', $_POST["new_password"] );
            $database->update('wstaff', 
                ['wpassword' =>$new_password],
                ['wno' => $_SESSION["id"] ]
                );
            echo "密码修改成功！";
        }
        else
            echo "原密码输入不正确！";
    }
    else 
        echo "密码提交错误！";
    // echo $_POST["origin_password"];
    // echo $_POST["new_password"];
    // echo $_POST["confirm_password"];
?>