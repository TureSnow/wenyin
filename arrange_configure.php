<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php 
if(isset($_SESSION["id"])){
	if((int)($_SESSION["privilege"])>1){
		echo "<script language=javascript>alert('只对管理员可见！');location.href='./shift.php';</script>";
	}
}else
echo "<script language=javascript>alert('请先登录！');location.href='/login.php';</script>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>选班排班系统</title>
	<?php include 'format/head.php'; ?>
	<script src="https://cdn.bootcss.com/vue/2.2.2/vue.min.js"></script>
</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('arrange_configure');
		p.setAttribute('class', 'active'); 
	</script>

    <div class="container-fluid" id="inbody">
      <div class="row-fluid">
      
<div id="app">
		<hr>
        <b>排班周数: </b>
		<input v-model="weeks" id="ipp1" />
        <b>每日班数: </b>
		<input v-model="shifts_per_day" id="ipp2"/>
		<br>
		<br>
		<b>每班时长(小时): </b>
		<table class="table-responsive">
			<tbody>
				<tr id="id1" v-for="i in shifts_per_day " >
					<td>第{{i}}班: <input  type="number" name="points" min="1" max="100" style="width:30px" v-model="duration_of_shift[i-1]"/></td>
				<tr>
			</tbody>
		</table>
		<hr>
		<!-- <b>排班模式：</b>    
		<select v-model="privilege_model">
		<option value =1>平行模式</option>
	    </select>
		<hr> -->
	   <ul v-for="wid in weeks">
	   <li  class="row-fluid">
			<table class="table-responsive">
				<thead>
					<tr>
					<td><input v-model = "weeks_name[wid-1]"/></td>
					<td v-for="day in 7"> {{day}} </td>
					</tr>
				</thead>
				<tbody v-for="i in shifts_per_day">
					<tr >
						<td>第{{i}}班:</td>
						<td v-for="j in 7"><input type="number" name="points" min="1" max="100" style="width:30px"  v-model="staffs_of_shift[j-1 + (i-1)*7 + (wid-1)*shifts_per_day*7]"/></td>
					</tr>
				</tbody>
			</table>
		</li>
	   </ul>

		<hr>
		<button @click="update_config" class="btn btn-large btn-primary" id="handon">提交设置</button>
</div><!-- end of app  -->
      <hr>
	  <div>
    </div><!--/.fluid-container-->

</body>
<script>
var search_url = "search_info.php";
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

var Weeks = parseInt(custom_info[0]);
var Shifts_per_day =parseInt(custom_info[1]);

var Total_shifts= Weeks*Shifts_per_day*7;

var Duration_of_shift = new Array(Shifts_per_day);
for(i=0; i<Shifts_per_day; i++)
    Duration_of_shift[i]=3;

var Weeks_name = new Array(Weeks);
for(i=0; i<Weeks; i++)
    Weeks_name[i]="week"+(i+1);

var Staffs_of_shift = new Array(Total_shifts);
for(i=0; i<Total_shifts; i++)
    Staffs_of_shift[i]=3;
</script>
<script type="text/javascript">
//vue.js 相关脚本
		var Data = {
			weeks:  Weeks,
			shifts_per_day: Shifts_per_day,
			total_shifts: Total_shifts,
			duration_of_shift: Duration_of_shift,
			weeks_name: Weeks_name,
			privilege_model: 1,
			staffs_of_shift: Staffs_of_shift,
            }

		var Watch= {
			"weeks": function(val){
				this.weeks = val;
                this.update_custom(val, this.shifts_per_day);
				location.reload();	      
			},
			"shifts_per_day": function(val){
				this.shifts_per_day = val;
				this.update_custom(this.weeks, val);
				location.reload();
            }
		}
		var Mounted = function(){
			}
		var Methods = {
			update_custom:function(var1, var2){
			$.ajax({
				url:"operation/up_custom.php",
				type:"post",
				// dataType:'json',
				data:{"weeks":var1, "shifts_per_day":var2},
				async: true,
				success:function(data){
					alert("修改成功");
				},
				error:function(e){
					alert("加载失败");
				}
			});
		},
			update_config: function(){
				data_all = { "weeks": this.weeks,
							"shifts_per_day": this.shifts_per_day};

                for(i=1; i<=this.shifts_per_day; i++)
					data_all['time'+i] = this.duration_of_shift[i-1];

				for(i=1; i<=this.total_shifts; i++)
					data_all[i] = this.staffs_of_shift[i-1];
				for(i=1; i<=this.weeks; i++)
					data_all["week"+i] = this.weeks_name[i-1];
			
				$.ajax({
					url:"operation/custom.php",
					type:"post",
					data: data_all,
					success:function(data){
						console.log("over..");
						alert("设置权限成功！");
					},
					error:function(e){
						alert("提交失败！");
					}
				});	
			},                
        }
        var vm = new Vue({
            el: '#app',
            data: Data,
			mounted: Mounted,
            methods: Methods,
			watch: Watch,
        })

	

	// $("#ipp1").bind('input porpertychange',function(){
	// 	update_custom(Data.weeks, Data.shifts_per_day);
	// 	location.reload();
	// 	});
	// $("#ipp2").bind('input porpertychange',function(){
	// 	update_custom(Data.weeks, Data.shifts_per_day);
	// 	location.reload();
	// 	});
</script>
</html>
