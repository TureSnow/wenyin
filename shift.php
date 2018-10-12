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
	<script src="https://cdn.bootcss.com/vue/2.2.2/vue.min.js"></script>
	<style>
		.itemDiv {
			margin-top:0px;
			margin: 0px auto;
		}
	</style>

</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('shift');
		p.setAttribute('class', 'active');
	</script>

    <div class="container-fluid">
      <div class="row-fluid">

        <div class="span9" id="app"  >
          <div class="hero-unit" >
            <h1 id="hello">Hello, world!</h1>
            <p id="msg">请在13月25点之前完成填班工作</p>
            <!-- <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p> -->
          </div> 
		  <!-- 填班的表格 -->
        
		<div id="tf">
		<table  v-for="weekid in weeks"  class="table table-bordered">
			<thead>
				<tr>
				    <th>{{week_name[weekid]}}</th>
					<th v-for="day in 7"> 周{{day}} </th>
				</tr>
			</thead>
			<tbody v-for="i in shifts_per_day">
				<tr >
					<td>第{{i}}班</td>
					<td v-for="j in 7" style="background-color: #ECF0F1" id="{{j + (i-1)*7 + (weekid-1)*shifts_per_day*7}}">
					<div  @click="click_select(j + (i-1)*7 + (weekid-1)*shifts_per_day*7)">
						<div v-if="all_selects[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]">
							<div  style="background-color: #3498DB" v-if="self_selects[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]"  >{{all_selects[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]}}</div>
							<div v-else >{{all_selects[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]}}</div>
							<!-- {{all_selects[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]}} -->
						</div>
						<div v-else>0</div>
					</div>
					</td>
				</tr>
			</tbody>
		</table>
		</div>

		</div><!--/span-->

      </div><!--/span-->
    </div><!--/row-->

      <hr>
    </div><!--/.fluid-container-->

</body>
 
<script>
    var search_url = "search_info.php";
	var select_shifts = (function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"if_select_shift":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					alert("加载失败");
				}
				});
			return result;
	})();


	var select_nums = (function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"shifts_select_num":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					alert("加载失败");
				}
				});
			return result;
	})();

	var custom_info = (function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"custom_info":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					alert("加载失败");
				}
				});
			return result;
	})();

	var weeksname =(function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"weeksname":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					alert("加载失败");
				}
				});
			return result;
	})();
</script>

<script>
    var Data= {
        weeks:parseInt( custom_info[0]),
		week_name: weeksname,
		shifts_per_day: parseInt( custom_info[1]),
        self_selects: select_shifts,
		all_selects:  select_nums,
    }
    var Methods= {
	   click_select: function(shift_id){
		   //先更新数据
		   //再检查颜色
		   var flag=1;
		   if(this.self_selects[shift_id]=='1')
               flag=0;

		//    var block = '#'+shift_id;
        //    if(flag) //id颜色变亮
		// 	 $(block).css("background-color","yellow");
		//    else //id颜色变暗
		//      $(block).css("background-color","green");

		    //flag =1, 插入
             
		   	$.ajax({
				url:"operation/click_select.php",
				type:"post",
				data:{"shift_id":shift_id, "flag":flag},
				async: false,
				success:function(data){
					console.log("over..");
					// alert("提交成功！");
					location.reload();
				},
				error:function(e){
					alert("提交失败！");
				}
		    });	
	   },
	   display_selfshift:function(){
           //显示所有班人数基础上，高亮显示已经选的班
		   for (var x in this.self_selects) {
			  $('#'+x).css("background-color","#3498DB");
			}
	   },
    }
	var Mount =function(){
		for (var x in this.self_selects) {
			  $("#"+x).css("background-color","#3498DB");
			}
	}

    var vm = new Vue({
        el: '#app',
        data: Data,
        methods: Methods,
		mount:Mount,
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
			$("#msg").text("点击灰色选班~");
		}
	});

</script>
</html>
