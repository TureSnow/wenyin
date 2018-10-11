<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once 'format/.conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php 
if(isset($_SESSION["id"])){
	if((int)($_SESSION["privilege"])>1){
		echo "<script language=javascript>alert('对不起，您没有权限！');location.href='./shift.php';</script>";
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
        <b>排班天数: </b>
		<input v-model="arrange_days"    />
        <hr>

		<b>每日班数: </b>
		<input v-model="arrange_shifts"  />
		<br>
		<br>
		<b>每班时长(小时): </b>
		
		<table class="table-responsive">
			<tbody v-for="(Shift_time,timeofshift) in during_of_shift">
				<tr> <td> 第{{timeofshift+1}}班：<input v-model="Shift_time"/></td> </tr>
			</tbody>
		</table>
		<hr>

		<b>权限数目：</b>
        <input v-model="privilege_nums" />
		<br>
		<br>

		<b>权限名称：</b>
		<table class="table-responsive">
			<tbody v-for="(name, nums) in privilege_name">
				<tr> <td>权限{{nums+1}}：<input v-model="name"/> </td> </tr>
			</tbody>
		</table>
		<hr>

		<b>排班模式：</b>    
		<select v-model="privilege_model">
		<option value =1>平行模式</option>
		<option value =2>等级模式</option>
	    </select>
		<hr>

		<b>每班人数</b>
		<table class="table-responsive">
		    <thead>
				<tr>
				 <td></td>
				 <td v-for="(a, NUM) in privilege_name"> {{a}} </td>
				</tr>
			</thead>
			<tbody v-for="(shift, timeofShift) in staffs_of_shift">
				<tr >
					<td>第{{timeofShift + 1}}班:</td>
					<td v-for="(during,p_nums) in shift"><input v-model="during"/></td> 
				</tr>
			</tbody>
		</table>
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
			    arrange_days: 7,
			    arrange_shifts: 4,
                during_of_shift: [3, 3, 3, 3],
				privilege_nums: 2,
                privilege_name: ["中层经理", "员工"],
				privilege_model: 1, // 1: 平行，所有人权限相同  2: 递减，高级可以当低级班
				staffs_of_shift: [ [1,2], [1,2], [1,2], [1,2] ],
            }

		 var Watch= {
			    arrange_shifts: function(val){
					this.arrange_shifts = val;
					this.during_of_shift = new Array(val);
					for (i = 0; i < val; i++) { 
						this.during_of_shift[i] = 3;
					}
					//同时会引起staffs表格的变化
					// this.staffs_of_shift = new Array(this.arrange_shifts, this.privilege_nums);
					this.staffs_of_shift.length= this.arrange_shifts;
					for(i=0; i< this.arrange_shifts; i++)
					{
					   this.staffs_of_shift[i]=[];
					   for(j=0; j< this.privilege_nums; j++)
					        this.staffs_of_shift[i][j] = 1;
					}
					      
				},
				privilege_nums: function(val){
					this.privilege_nums = val;
					this.privilege_name = new Array(val);
					for (i = 0; i < val; i++) { 
						this.privilege_name[i] = "Level"+(i+1);
					}
				 //同时会引起staffs表格的变化
					// this.staffs_of_shift = new Array(this.arrange_shifts, this.privilege_nums);
					for(i=0; i<this.arrange_shifts; i++)
					   for(j=0; j<this.privilege_nums; j++)
					        this.staffs_of_shift[i][j] = 1;   
				},
           }
		var Mounted = function(){
			//onload
			//从数据库里加载属性，并显示
				// alert("test!!!");
			}

		var Methods = {  
			update_config: function(){
				//将目前数据保存到数据库中
                alert("您的设置已保存");
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
