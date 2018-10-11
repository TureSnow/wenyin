<!DOCTYPE html>
<html>
<head>
    <title>注册</title>
    <?php  include "format/head.php"?>
	<style>
            body {
                padding-top: 60px;
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
            <form class="form-signin"  method="post">
                <h2 class="form-signin-heading">Register</h2>
                <input type="text" class="input-block-level" placeholder="Email address">
                <input type="password" class="input-block-level" placeholder="Password">
                <input type="password" class="input-block-level" placeholder="repeat your Password">

                <input type="text" class="input-block-level" placeholder="请输入验证码">

                <input type="button" class="btn btn-large"  value="获取验证码"/>
                <!-- onclick="location.href='get_code.php';" -->
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-large btn-primary" type="submit">Submit</button>
            </form>
        </div> <!-- /container -->
    </body>
</html>
