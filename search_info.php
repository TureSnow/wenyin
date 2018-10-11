<?php
    //返回所有人的选班结果
    session_start();
    header("Content-type: text/html; charset=utf-8");
    require_once("../conf.ini");
    require_once 'medoo/Medoo.php';
    use Medoo\Medoo;

    if(!isset($_SESSION["id"])) return;
    //管理员权限

    $database = new Medoo([
            'database_type' => 'mysql',
            'database_name' => $DBNAME,
            'server' =>  $DBHOST,
            'username' => $DBUSER,
            'password' => $DBPWD,
        ]);


    //所有选班的人-->字典
     if(isset($_POST['staffs_who_selects'])){
        $results = $database->select("select_shift", ["sno","wno"]);
        $selects = array();
        
        foreach ($results as $v) {
            if(!isset( $selects[$v["sno"]]))
                $selects[$v["sno"]] =  array();
                
            array_push($selects[$v["sno"]], $v['wno']);
        }
        echo json_encode($selects);
        return;
    }
    
    //所有排班的人-->字典
    if(isset($_POST['staffs_who_arranged'])){
        $results = $database->select("arrange_shift", ["sno","wno"]);
        $arranged = array();
      
        foreach ($results as $v) {
            if(!isset( $arranged[$v["sno"]]))
                 $arranged[$v["sno"]] =  array();
               
            array_push($arranged[$v["sno"]], $v['wno']);
        }
        echo json_encode($arranged);
        return;
    }
   
    
    //每班人数-->字典
    if(isset($_POST['shifts_select_num'])){

            $results = $database->select("select_shift", ["sno",'sum' => Medoo::raw('COUNT(<wno>)')],[
                'GROUP' => 'sno',
            ]);
            $selects = array();
            foreach ($results as $v) {
                $selects[$v["sno"]] = $v["sum"];
            }
    
            echo json_encode( $selects);
            return;
        }

    //号码到名字-->字典
    if(isset($_POST['no2name'])){
            $results = $database->select("wstaff", ["wno","wname"]);
           
            $dics = array();
            foreach($results as $v)
                $dics[$v["wno"]] = $v["wname"];
            
            echo json_encode($dics);
        }
    //某个人所有选的班
    if(isset($_POST['if_select_shift'])){
        $results = $database->select("select_shift", ["sno"],[
            "wno" => $_SESSION["id"]
        ]);
        $selects = array();

        foreach ($results as $v) {
            $selects[$v["sno"]] = 1;
        }
        echo json_encode($selects);
        return;
        }
    //所有员工的信息
    if($_POST["get_staff"]){
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
                    array_push($staff,$value);
                    #echo $value;
                }
                
                array_push($staffs, $staff);
                // array_push($staffs, $row);
                // echo $row['wname'] . "\n";
                #$staffs=$staffs . 
            }
        }

        //将 $staffs数组返回给客户端
        echo json_encode($staffs);
        return;
    }
    
    //定制排班的两个参数
    if(isset($_POST['custom_info'])){

        $results = $database->select("custom", ["weeks","shift_per_day"]);
        $custom_info = array();
        $custom_info[0] = $results[0]["weeks"];
        $custom_info[1] = $results[0]["shift_per_day"];
        echo json_encode($custom_info);
        return;
    }
    //周的名字
    if(isset($_POST['weeksname'])){
        $weeksname = array();
        $results = $database->select("weekinfo", ["weekno","weekname"]);
        foreach($results as $v){
            $weeksname[$v["weekno"]] = $v["weekname"];
        }
        echo json_encode($weeksname);
        return;
    }
 ?>
