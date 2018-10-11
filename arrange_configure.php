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
		<input v-model="weeks"  />
        <b>每日班数: </b>
		<input v-model="shifts_per_day"  />
		<br>
		<br>
		<b>每班时长(小时): </b>
		<table class="table-responsive">
			<tbody v-for="i in shifts_per_day ">
				<tr><td>第{{i}}班: <input  type="number" name="points" min="1" max="100" style="width:30px" v-model="duration_of_shift[i-1]"/></td><tr>
			</tbody>
		</table>
		<hr>


		<!-- <b>排班模式：</b>    
		<select v-model="privilege_model">
		<option value =1>平行模式</option>
	    </select>
		<hr> -->
       
	   <ul v-for="wid in weeks" >
	   名称:<input v-model = "weeks_name[wid-1]"/>
	   <li class="row-fluid">
			<table class="table-responsive">
				<thead>
					<tr>
					<td></td>
					<td v-for="day in 7"> {{day}} </td>
					</tr>
				</thead>
				<tbody v-for="i in shifts_per_day">
					<tr >
						<td>第{{i}}班:</td>
						<td v-for="j in 7"><input type="number" name="points" min="1" max="100" style="width:30px"  v-model="staffs_of_shift[ j-1 + (i-1)*7 + (wid-1)*shifts_per_day*7]"/></td>
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
<script type="text/javascript">
//vue.js 相关脚本
		var Data = {
			    weeks: 2,
			    shifts_per_day: 4,
				total_shifts: 28,
                duration_of_shift: [3,3,3,3],
                staffnum_of_shift: [3, 3, 3, 3],
				weeks_name:["本部","北区"],
				// privilege_nums: 2,
                // privilege_name: ["中层经理", "员工"],
				privilege_model: 1,       // 1: 平行，所有人权限相同  2: 递减，高级可以当低级班
				staffs_of_shift: [ 2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3 ],
            }

		 var Watch= {
			   shifts_per_day: function(val){
					this.shifts_per_day = val;
					this.duration_of_shift = new Array(val);
					for (i = 0; i < val; i++)
						this.duration_of_shift[i] = 3;
					//同时会引起staffs表格的变化
					// this.staffs_of_shift = new Array(this.arrange_shifts, this.privilege_nums);
					this.total_shifts =this.weeks * 7 * this.shifts_per_day;
					this.staffs_of_shift.length= this.total_shifts;
					for(i=0; i< this.total_shifts; i++)
					   this.staffs_of_shift[i]=3;      
				},
				weeks: function(val){
					this.weeks = val;
					//同时会引起staffs表格的变化
					// this.staffs_of_shift = new Array(this.arrange_shifts, this.privilege_nums);
					this.weeks_name = new Array(val);
					this.total_shifts =this.weeks * 7 *this.shifts_per_day;
					this.staffs_of_shift.length= this.total_shifts;
					for(i=0; i< this.total_shifts; i++)
					   this.staffs_of_shift[i]=3;      
				},
           }
		var Mounted = function(){
			//将数据库里的信息加载到此
			//onload
			//从数据库里加载属性，并显示
				// alert("test!!!");
			}

		var Methods = {  
			update_config: function(){
				$.ajax({
					url:"operation/custom.php",
					type:"post",
					data:{  "weeks": this.weeks,
							"shifts_per_day": this.shifts_per_day,
							"total_shifts": this.total_shifts,
							"duration_of_shift":this.duration_of_shift,
							"staffnum_of_shift": this.staffnum_of_shift,
							"weeks_name":this.weeks_name,
							"staffs_of_shift": this.staffs_of_shift,
							},
					processData:false,
					contentType:false,
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
</script>
</html>
