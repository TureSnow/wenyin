<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once 'format/.conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php
if(isset($_SESSION["id"])){
	if((int)($_SESSION["privilege"])>2){
		echo "<script language=javascript>alert('对不起，您没有权限！');location.href='/index.php';</script>";
	}
}else
echo "<script language=javascript>alert('请先登录！');location.href='/login.php';</script>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>选班排班系统</title>
	<?php include 'format/head.php'; ?>
	<style>
			body {
			  padding-top: 80px; /* 60px to make the container go all the way to the bottom of the topbar */
			}
	</style>

</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('in_out');
		p.setAttribute('class', 'active'); 
	</script>

    <div class="container-fluid">
    <div class="row-fluid">

        <div class="span9">
          <div class="hero-unit">
            <h1>敬请期待</h1>
            <p>收班出版板块</p>
            <!-- <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p> -->
          </div> 
    
      <hr>

    </div><!--/.fluid-container-->

</body>
</html>
