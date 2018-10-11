<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once 'format/.conf.ini';
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

<!-- <?php
	echo date("Y-m-d")."&nbsp;&nbsp;";
	echo date("l");
?> -->

<!--    <hr> -->
   <div class="container-narrow">
		<div class="jumbotron">
				<h1>欢迎来到光华排班系统</h1>
				<p class="lead">可定制排班，满足光华人的排班需求</p>
				<a class="btn btn-large btn-success" href="shift.php">进入排班</a>
		</div>
	<hr>

	<div class="row-fluid marketing">
        <div class="span6">
          <h3>文印业务简介</h3>
          <p>打印、复印、卖纸、扫描、期末资料、奖状等</p>
        
          <br>
          <h3>价格明细</h3>
          <ul>
            <li>A4(70g)：0.1元/面</li>
            <li>A4(80g)：单面0.2元、双面0.3元</li>
            <li>A3(70g)：单面0.4元、双面0.6元</li>
            <li>彩打(每面)：彩色面积小于1/2、1元；大于1/2、2元 </li>
            <li>彩纸(红黄蓝绿)：单面0.4元、双面0.5元 </li>
            <li>扫描/奖状：0.5元/面</li>
          </ul>
        </div>

        <div class="span6">
          <h3>顾客投诉</h3>
          <p>向我们反馈</p>
          <br>
          <h3>资料种类</h3>
          <ul>
            <li>《我是马基》</li>
            <li>《我是毛概》</li>
            <li>《我是军理》</li>
            <li>《我是高数》(ABC)</li>
            <li>《我是数分》</li>
            <li>《我是大物》</li>
          </ul>
        </div>
	</div>
  <hr>
	  
	</div> 
</body>
</html>
