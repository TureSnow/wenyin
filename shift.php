<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php 
if(isset($_SESSION["id"])){
	if((int)($_SESSION["privilege"])>2){
		echo "<script language=javascript>alert('对不起，您没有权限！'); location.href='/index.php';</script>";
	}
}else
echo "<script language=javascript>alert('请先登录！');location.href='/login.php';</script>";

use Medoo\Medoo;
$database = new Medoo([
  'database_type' => 'mysql',
  'database_name' => $DBNAME,
  'server' =>  $DBHOST,
  'username' => $DBUSER,
  'password' => $DBPWD,
]);

$data = $database->select('flags', "flag_value", ['flag_name'=> 'allow_shift' ]  );
$allow_shift = $data[0];

?>
<!DOCTYPE html>
<html>
<head>
	<title>选班排班系统</title>
	<?php include 'format/head.php'; ?>

</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('shift');
		p.setAttribute('class', 'active');
	</script>

    <div class="container-fluid">
      <div class="row-fluid">

        <div class="span9">
          <div class="hero-unit">
            <h1 id="hello">Hello, world!</h1>
            <p id="msg">请在13月25点之前完成填班工作</p>
            <!-- <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p> -->
          </div> 
		  <!-- 填班的表格 -->

		<form id="tf">
		<table class="table table-hover  table-responsive">
			<thead>
				<tr>
				<th></th>
				<th>周一</th>
				<th>周二</th>
				<th>周三</th>
				<th>周四</th>
				<th>周五</th>
				<th>周六</th>
				<th>周日</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>第一班</td>
					<td><input type="checkbox" name="shift1" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift5" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift9" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift13" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift17" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift21" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift25" value="1" style="zoom:180%;"/> </td>
				</tr>
				<tr>
					<td>第二班</td>
					<td><input type="checkbox" name="shift2" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift6" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift10" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift14" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift18" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift22" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift26" value="1" style="zoom:180%;"/> </td>
				</tr>
				<tr>
					<td>第三班</td>
					<td><input type="checkbox" name="shift3" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift7" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift11" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift15" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift19" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift23" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift27" value="1" style="zoom:180%;"/> </td>
				</tr>
				<tr>
					<td>第四班</td>
					<td><input type="checkbox" name="shift4" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift8" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift12" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift16" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift20" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift24" value="1" style="zoom:180%;"/> </td>
					<td><input type="checkbox" name="shift28" value="1" style="zoom:180%;"/> </td>
				</tr>
			</tbody>
		</table>
		<input type="button" value="提交" onclick="upload();"/> &nbsp &nbsp
		<button type="reset">重置</button>
		</form>
		</div><!--/span-->



      </div><!--/span-->
    </div><!--/row-->

      <hr>
    </div><!--/.fluid-container-->

</body>
 
<script>
    var Data= {
        weeks: 2,
		week_name:["本部","北区"],
		shifts_per_day: 4,
        self_selects:{},
		all_selects: {},
    }
    var Methods= {
       main_func:function(){
       },
       read_shift_info:function(){
		   /* 得到如下信息:
		          1. 星期数 + 每星期的名字
				  2. 每日班数
		   */
	   },
	   display_shift:function(){
            /*
			 根据班的信息，显示表格
			*/
	   },
	   click_select: function(shift_id){
		   this.read_all_shift();
		   this.display_slefshift();
		   //检测此班是不是已选
		   //提交数据库提交一条记录
		      //根据提交结果反转颜色
		   
	   },
	   display_selfshift:function(){
            //显示所有班人数基础上，高亮显示已经选的班
	   },
       read_all_shift: function(){
		   //显示所有班的人数
	   },

    }
    var vm = new Vue({
        el: '#app',
        data: Data,
        methods: Methods,
    })

	$( document ).ready(function() {
		var flag= "<?php echo $allow_shift;?>";
		var wname= "<?php echo $_SESSION['wname'];?>";
		$("#hello").text("Hello, "+wname);
		if(flag=="0"){
			$("#tf").hide();
			$("#msg").text("暂未开放选班~~");
		}
		else{
			$("#msg").text("请尽快完成选班~~");
		}
	});
//提交表单文件的函数
	function upload(){
		var form = new FormData(document.getElementById("tf"));
		$.ajax({
			url:"operation/get_shift.php",
			type:"post",
			data:form,
			processData:false,
			contentType:false,
			success:function(data){
				console.log("over..");
				alert("提交成功！");
			},
			error:function(e){
				alert("提交失败！");
			}
		});	
	}
</script>
</html>
