<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../conf.ini';
require_once 'medoo/Medoo.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>可定制排班系统</title>
  <?php include './format/head.php'; ?>
	<style>
	  body {
        padding-bottom: 40px;
      }
    /* Custom container */
    .container-narrow {
      margin: 0 auto;
      max-width: 700px;
    }
    .container-narrow > hr {
      margin: 30px 0;
    }
    /* Main marketing message and sign up button */
    .jumbotron {
      margin: 60px 0;
      text-align: center;
    }
    .jumbotron h1 {
      font-size: 72px;
      line-height: 1;
    }
    .jumbotron .btn {
      font-size: 21px;
      padding: 14px 24px;
    }
    /* Supporting marketing content */
    .marketing {
      margin: 60px 0;
    }
    .marketing p + h4 {
      margin-top: 28px;
    }
	</style>
</head>
<body>
	<?php include './format/menu.php'; ?>
	<script>
		var p = document.getElementById('main_page');
		p.setAttribute('class', 'active'); 
	</script>

<!--    <hr> -->
   <div class="container-narrow">
		<div class="jumbotron">
				<h1>Welcome</h1>
				
				<a class="btn btn-large btn-success" href="shift.php">进入排班</a>
		</div>
	<hr>

	<div class="row-fluid marketing">
   <p class="lead">目前当班表</p>
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
	  
	</div> 
</body>
</html>
