<?php
session_start(); 
header("Content-type: text/html; charset=utf-8");
require_once '../conf.ini';
require_once 'medoo/Medoo.php';
?>

<?php 
if(isset($_SESSION["id"])){

    //取员工信息，并放在一个数组中；
    $conn = new mysqli($DBHOST, $DBUSER, $DBPWD , $DBNAME);
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
        return;
    } 
    $sql = "select wno, wname, wprivilege from wstaff;";
    $result = $conn->query($sql);

    //$staffs 保存员工信息
    $staffs = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $staff = array();
            foreach($row as $key => $value){
                array_push($staff, $value);
                #echo $value;
            }
            array_push($staffs, $staff);
            // array_push($staffs, $row);
            // echo $row['wname'] . "\n";
            #$staffs=$staffs . 
        }
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
	<!-- <style>
			body {
			  padding-top: 80px; /* 60px to make the container go all the way to the bottom of the topbar */
			} 
	</style> -->

</head>
<body>
	<?php include 'format/menu.php'; ?>
	<script>
		var p = document.getElementById('manage');
		p.setAttribute('class', 'active'); 
	</script>

    <div class="container-fluid">
      <div class="row-fluid">
      <hr>
<!--    上传通讯录操作     <input class="input-block-level" type="file" id="file-upload" name="file"/>
        <br>
        <button class="btn btn-large btn-primary" onclick="onload_staff()">导入通讯录</button>
        <hr> -->
        <button class="btn btn-large btn-primary" onclick="show_staff()">添加新员工</button>
        <button class="btn btn-large btn-primary" onclick="show_staff()" id="show_staffs" >显示员工信息</button>
        <button class="btn btn-large btn-primary" onclick="show_staff()" id="confirm" >确认设置</button>
        <hr>
<div id="app">
  <!-- 此处显示员工信息 -->
   <table id="staffs_table" class="table-responsive">
        <thead>
        <tr>
         <td>账号</td>
         <td>名称</td>
         <td>权限</td>
         <td>删除</td>
        </tr>
        </thead>
        <tbody v-for="staff in staffs" >
            <tr>
                    <td v-for="(info, nums_n) in staff">
                        <div v-if="nums_n == 2">
                            <select v-model="info">
                                <option value =0>管理员</option>
                                <option value =1>中高层经理</option>
                                <option value =2>员工</option>
                                <option value =3>被限制员工</option>
                            </select>
                        </div>
                        <div v-else-if="pri > 0">
                            <input v-model="stars"/>
                        </div>
                        <div v-else>
                            <input v-model="info"/>
                        </div>
                    </td> 
                    <td><button class="btn" onclick="show_staff()" id="show_staffs" >删除</button></td>
            </tr>
        </tbody>
    </table>
</div> <!-- / app-->
	  <div>
    </div><!--/.fluid-container-->
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $("#staffs_table").hide();
        $("#confirm").hide();
    });

    $("#show_staffs").click(function(){
        if($("#staffs_table").is(":hidden")){  
            $("#show_staffs").text("隐藏员工信息");
            $("#staffs_table").show();
            $("#confirm").show();
        } 
        else {
            $("#show_staffs").text("显示员工信息");
            $("#staffs_table").hide();
            $("#confirm").hide();
        }
    });
    function onload_staff(){
            alert("通讯录成功导入")
    }
    function show_staff(){
    }
</script>

<script type="text/javascript">

    var Data= {
        staffs:[],
        pri: 1,
        stars: "********",
    }

    var Mounted = function(){
       //调用php的员工信息
      this.staffs = '<?php 
       $staffs_json = json_encode($staffs);
       echo ($staffs_json);
       ?>';
      this.staffs = eval("("+ this.staffs+")")
      this.pri = parseInt('<?php echo $_SESSION["privilege"]?>');
		}

    var Methods= {}
    var Watch= {}
    var vm = new Vue({
        el: '#app',
        data: Data,
        mounted: Mounted,
        methods: Methods,
        watch: Watch,
    })
</script>
</html>
