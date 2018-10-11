<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once 'format/.conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php 

if(isset($_SESSION["id"])){
  if((int)($_SESSION["privilege"])>2){
    echo "<script language=javascript>alert('对不起，您没有权限！'); location.href='/index.php';</script>";
  }
}else
echo "<script language=javascript>alert('请先登录！');location.href='/login.php';</script>";

?>

<html>
<head>
	<title>选班排班系统</title>
	<?php include 'format/head.php'; ?>
</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('table');
		p.setAttribute('class', 'active'); 
	</script>

    <div class="container-fluid">
    <div class="row-fluid">

    <div class="span9">
        <div class="hero-unit">
            <h1>当班表</h1>
            <p></p>
            <!-- <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p> -->
        </div>
<div>
   <table width="100%" class="table table-striped table-bordered table-hover">
     <tr style="height: 50px;">
       <th>当班表</th>
       <th>星期一</th>
       <th>星期二</th>
       <th>星期三</th>
       <th>星期四</th>
       <th>星期五</th>
       <th>星期六</th>
       <th>星期日</th>
     </tr>
   <?php require_once 'operation/result_table.php'; ?>
   </table>
</div> 
   <hr>
   </div><!--/.fluid-container-->

</body>
</html>
