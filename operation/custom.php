<?php
session_start();
header("Content-type: text/html; charset=utf-8");
require_once("../../conf.ini");
require_once '../medoo/Medoo.php';
use Medoo\Medoo;

if(!isset($_SESSION["id"])) return;
if((int)($_SESSION["privilege"])>0)
return ;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => $DBNAME,
    'server' =>  $DBHOST,
    'username' => $DBUSER,
    'password' => $DBPWD,
]);

if(isset($_POST)){
   $weeks = $_POST["weeks"];
   $shifts_per_day = $_POST["shifts_per_day"];

   $database -> update("custom", [
	"weeks" => $weeks,
	"shifts_per_day" =>$shifts_per_day,
   ]);

   $database -> delete("weekinfo",[]);
   $database -> delete("select_shift",[]);
   $database -> delete("arrange_shift",[]);
   $database -> delete("shift",[]);

   $total_shifts = $weeks * $shifts_per_day *7;

   $weeksname = array();
   for($i=1; $i<=$weeks; $i++)
        $weeksname[$i] = $_POST["week".$i];

   $duration = array();
   for($i=1; $i<=$shifts_per_day; $i++)
        $duration[$i] = $_POST["time".$i];

   $shift_nums = array();
   for($i=1; $i<=$total_shifts; $i++)
        $shift_nums[$i] = $_POST[$i.""];

    for($i=1; $i<=$weeks; $i++)
        $database->insert('weekinfo', [
            'weekno' => $i,
            'weekname' =>  $weeksname[$i]
        ]);
   
    for($i=1; $i<=$total_shifts; $i++){
        $database->insert('shift', [
            'sno' => $i,
            'snum' => $shift_nums[$i],
            'stime' => $duration[(($i-1)%($shifts_per_day * 7))/7+1],
        ]);
    }
}
?>
