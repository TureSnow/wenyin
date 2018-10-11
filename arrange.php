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
		var p = document.getElementById('arrange');
		p.setAttribute('class', 'active'); 
	</script>
  
<div class="container-fluid">
    <div class="row-fluid">

    <div class="span9" id="app">
    <hr>
    <button  class="btn btn-default" onclick="new_select();" >初始化排班 </button>
        <!-- <button class="btn btn-default" onclick="stop_select();">结束选班</button> -->
        <button class="btn btn-default" onclick="arrange();" href="#">开始排班</button>
        <button class="btn btn-default" onclick="reopen_select();" href="#">重开选班</button>
        <button class="btn btn-default"  onclick="release();" href="#" >发布排班表</button>
        <a class="button btn btn-default"  download="排班表.csv" href="./operation/result_table.csv" >下载排班表</a>
        <hr>
    <div>
       <table  v-for="weekid in weeks" width="100%" class="table table-striped table-bordered table-hover">
       <thead>
            <tr style="height: 50px;">
                <th>{{week_name[weekid-1]}}</th>
                <th v-for="day in 7"> 周{{day}} </th>
            </tr>
		</thead >

        <tbody v-for="i in shifts_per_day">
            <tr >
                <td>第{{i}}班</td>
                <td v-for="j in 7">
                  =排班的人
                  <li v-for="person in  arrange_of_each_shift[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]">
                    <!-- <div  v-if="arrange_of_each_shift[j + (i-1)*7 + (weekid-1)*shifts_per_day*7].indexOf(person) !=-1" style="background-color: yellow"> {{no2name[person]}} </div> -->
                    {{no2name[person]}}
                  </li>
                  =选班的人
                  <li v-for="person in  select_of_each_shift[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]">
                    <!-- <div  v-if="arrange_of_each_shift[j + (i-1)*7 + (weekid-1)*shifts_per_day*7].indexOf(person) !=-1" style="background-color: yellow"> {{no2name[person]}} </div> -->
                    {{no2name[person]}}
                  </li>   
                </td>

            </tr>
        </tbody>
       </table>
    </div>
    <hr>

    <table width="100%" class="table table-striped table-bordered table-hover">
        <thead>
                <th>姓名</th>
                <th>工时数</th>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>"
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
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(select_shifts);//已经可以获取数据了

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
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(select_nums);//已经可以获取数据了

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
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(custom_info);//已经可以获取数据了
    var no2name = (function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"no2name":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(no2name);//已经可以获取数据了

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
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(weeksname);//已经可以获取数据了
   //数据加载
    var staffs_who_selects = (function(){
		var result;
		$.ajax({
				url:search_url,
				type:"post",
				dataType:'json',
				data:{"staffs_who_selects":1},
				async: false,
				success:function(data){
					result = data;
				},
				error:function(e){
					console.log(e);
					alert("加载失败");
				}
				});
			return result;
	})();
    console.log(staffs_who_selects);//已经可以获取数据了
     
    var staffs_who_arranged = (function(){
        var result;
        $.ajax({
                url:search_url,
                type:"post",
                dataType:'json',
                data:{"staffs_who_arranged":1},
                async: false,
                success:function(data){
                    result = data;
                },
                error:function(e){
                    console.log(e);
                    alert("加载失败");
                }
                });
            return result;
    })();
    console.log(staffs_who_arranged);//已经可以获取数据了
</script>

<script>
    //vue部分
    var Data= {
        weeks:parseInt( custom_info[0]),
		week_name: weeksname,
		shifts_per_day: parseInt( custom_info[1]),
        self_selects: select_shifts,
		all_selects:  select_nums,
        select_of_each_shift: staffs_who_selects,
        arrange_of_each_shift:staffs_who_arranged,
    }
    var Methods={
    }
	var Mount =function(){
      

	}

    var vm = new Vue({
        el: '#app',
        data: Data,
        methods: Methods,
		mount:Mount,
    })

</script>

<script>   
    //操作函数
    function reopen_select(){
        $.ajax({
                url: "./operation/reopen_select.php",
                type: "post",
                data: {},
                processData: false,
                contentType: false,
                success: function(data){
                    console.log("over..");
                    alert("选班已重新开放！");
                },
                error: function(e){
                    alert("开放失败！");
                }
            });
    }

    //排班
    function arrange(){
        $.ajax({
                url:"./operation/arrange_shift.php",
                type:"post",
                data:{},
                processData:false,
                contentType:false,
                success:function(data){
                    console.log("over..");
                    alert("排班成功！");
                },
                error:function(e){
                    alert("排班失败！");
                }
            });
    }

    //发布当班表
    function release(){
    if(window.confirm("注意，此操作会清空原来的排班表!")){
        ;
    }else return; //若用户取消，则函数返回，不执行操作
        
        $.ajax({
                url:"./operation/release_result.php",
                type:"post",
                data:{},
                processData:false,
                contentType:false,
                success:function(data){
                    console.log("over..");
                    alert("发布成功！");
                },
                error:function(e){
                    alert("发布失败！");
                }
            });
    }


    //新建选班
    function new_select(){
    if(window.confirm("注意，此操作会清空上次选班的信息!")){
        ;
    }else return; //若用户取消，则函数返回，不执行操作

        $.ajax({
                url:"./operation/new_select.php",
                type:"post",
                data:{},
                processData:false,
                contentType:false,
                success:function(data){
                    console.log("over..");
                    alert("选班已经重新开放………");
                },
                error:function(e){
                    alert("初始化失败！");
                }
            });
    }
</script>
</html>
