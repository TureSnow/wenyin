<?php
    /*
    具体执行排班代码的php
    */

    session_start();
    header("Content-type: text/html; charset=utf-8");
    require_once("../../conf.ini");
    require_once '../medoo/Medoo.php';
    use Medoo\Medoo;

    //用session保护, //只有管理员可做此操作，权限为1可以看到，但无法操作
    if(!isset($_SESSION["id"]))
        return ;
    if((int)($_SESSION["privilege"])>0)
        return ;

    // 连接数据库
    $conn = new mysqli($DBHOST, $DBUSER, $DBPWD , $DBNAME);
	if ($conn->connect_error) {
		die("连接失败: " . $conn->connect_error);
    } 
    
    //结束选班环节, 标志位设置为0, 代表当前选班关闭
	$sql="update flags set flag_value = 0 where flag_name = 'allow_shift';";
    $result_nouse = $conn->query($sql);

	$sql = "SELECT wno,sno FROM select_shift;";
	$result = $conn->query($sql);
    $conn->close();

    $select_map = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {

            $wno= $row["wno"];
            $sno= (int)($row["sno"])-1;

            if(isset($select_map[$wno]))
                array_push($select_map[$wno], $sno);
            else 
                $select_map[$wno]=array($sno);
		}
    }

     // 稍后需要将班次信息写入到文件中
    //读班次信息
    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => $DBNAME,
        'server' =>  $DBHOST,
        'username' => $DBUSER,
        'password' => $DBPWD,
    ]);
    $custom = $database->select("custom", ["weeks","shift_per_day"]);
   
    $weeks = $custom[0]["weeks"];
    $shift_per_day = $custom[0]["shift_per_day"];

    $shift_info = $database->select("shift", ["sno","snum","stime"]);
    $shift_time = array();
    $shift_num = array();

    foreach($shift_info as $v){
        $shift_time[$v["sno"]] = $v["stime"];
        $shift_num[$v["sno"]]  =  $v["snum"];
    }

    $file=fopen("../../info2.txt","w") or exit("Unable to open file!");
    fwrite($file, "time_nums: ");
    $time_nums = $weeks * 7* $shift_per_day;
    fwrite($file, $time_nums."\n");

    fwrite($file, "duration\n");
    foreach ($shift_time as $key=>&$value){
        fwrite($file, $key." ");
        fwrite($file, $value);
        fwrite($file, "\n");
    }
    fwrite($file, "nums\n");

    foreach ($shift_num as $key=>&$value){
        fwrite($file, $key." ");
        fwrite($file, $value);
        fwrite($file, "\n");
    }
    fclose($file);
   
    // 选班信息写入到文件中
    $file=fopen("../../info.txt","w") or exit("Unable to open file!");
    foreach ($select_map as $key=>&$value){
        fwrite($file,$key." ");
        foreach($value as $v){
            fwrite($file,$v." ");
        }
        fwrite($file, "\n");
    }

    fclose($file);
    
    //排班
    $url='http://0.0.0.0:8086/arrange';
    $result = file_get_contents($url, false, $context);
    echo $result;

    
    sleep(1);//暂停0.3秒
    //sleep(1);

    //删除之前排班信息
    $database->query("DELETE from arrange_shift;"); 
    //读取排班结果，插入到数据库中
    $file=fopen("../../result.txt","r") or exit("Unable to open file!");
    while(!feof($file)) {
        $line=fgets($file);
        $arr= explode(" ", $line);
        echo $arr[0];
        $shift = (int)($arr[0])+1; //获取班
        for($i=1; $i<count($arr); $i++){
            $id=$arr[$i];
            //插入数据库中
            $database->insert('arrange_shift', [
                'wno' => $id,
                'sno' => $shift,
            ]);
        }
      }
    fclose($file);
?>