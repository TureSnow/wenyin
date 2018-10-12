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
  <script src="https://cdn.bootcss.com/vue/2.2.2/vue.min.js"></script>
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

	<div class="row-fluid marketing" id="app">
   <p class="lead">目前当班表</p>
       <table  v-for="weekid in weeks" width="100%" class="table table-striped table-bordered table-hover">
       <thead>
            <tr style="height: 50px;">
                <th>{{week_name[weekid]}}</th>
                <th v-for="day in 7"> 周{{day}} </th>
            </tr>
		</thead >
        <tbody v-for="i in shifts_per_day">
            <tr >
                <td>第{{i}}班</td>
                <td v-for="j in 7">
                  <li v-for="person in  arrange_of_each_shift[j + (i-1)*7 + (weekid-1)*shifts_per_day*7]">
                    {{no2name[person]}}
                  </li>
                </td>

            </tr>
        </tbody>
       </table>

	</div>
  <hr>
	  
	</div> 
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
</html>
