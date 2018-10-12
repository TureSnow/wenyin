<?php 
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../conf.ini';
require_once 'medoo/Medoo.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <?php  include "format/head.php"?>
	<style>
            body {
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }

                /* Custom container */
            .container-narrow {
            margin: 0 auto;
            max-width: 700px;
            }
            .container-narrow > hr {
            margin: 30px 0;
            }

            .form-signin {
                max-width: 300px;
                padding: 19px 29px 29px;
                margin: 0 auto 20px;
                background-color: #fff;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                        border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                        box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 10px;
            }
            .form-signin input[type="text"],
            .form-signin input[type="password"] {
                font-size: 16px;
                height: auto;
                margin-bottom: 15px;
                padding: 7px 9px;
            }
	</style>

</head>
    <body>
    <?php include "format/menu.php"; ?>
    <script>
            var p = document.getElementById('login_page');
            p.setAttribute('class', 'active');   
    </script>
        <div class="container-narrow">
        <!--  下面是提交登录信息的表单  -->
            <form class="form-signin" action="login.php" method="post">
                <h2 class="form-signin-heading">Please sign in</h2>
                <input type="text" name="userid" class="input-block-level" placeholder="Student Id of Fudan" > <!--placeholder="Email address">-->
                <input type="password" name="password" class="input-block-level" placeholder="Password">
                <label class="checkbox">
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
                <button class="btn btn-large btn-primary" type="submit">Sign in</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" class="btn btn-large " onclick="location.href='register.php';" value="Register"/>
            </form>
        </div> <!-- /container -->
    </body>
</html>

<?php
    use Medoo\Medoo;
    $database = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $DBNAME,
            'server' =>  $DBHOST,
            'username' => $DBUSER,
            'password' => $DBPWD,
        ]);

    //获取表单内容
    if(isset($_POST['userid'])){
        $id=   $_POST['userid'];
        $password = $_POST['password'];
        $pass=array();

        $pass= $database->select("wstaff", ["wpassword","wprivilege","wname"],[
            "wno" => $id
        ]);
    //"wprivilege" ,
        $password = hash('sha256', $password);    //求密码的哈希值
        // echo $id . "\n";
        // echo $password ."<hr>" ;
        // echo $pass[0]."<hr>";
        // echo $pass[1]. "<hr>";
        // echo count($pass,0). "<hr>";

        if(count($pass,0) ==1 && $pass[0]["wpassword"] == $password){
            $_SESSION["id"]= $id;
            $_SESSION["password"]= $password;
            $_SESSION["privilege"]= $pass[0]["wprivilege"];
            $_SESSION["wname"]= $pass[0]["wname"];
            echo "<script language=javascript>location.href='../index.php';</script>";
        }else{
            echo "<script language=javascript>alert('账号或密码错误！');</script>" ;
        }
    }
 ?>