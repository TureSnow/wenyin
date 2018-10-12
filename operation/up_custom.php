<?php
session_start();
header("Content-type: text/html; charset=utf-8");
require_once("../../conf.ini");
require_once '../medoo/Medoo.php';
use Medoo\Medoo;
if(!isset($_SESSION["id"])) return;
if((int)($_SESSION["privilege"])>0) return;

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
	"shift_per_day" =>$shifts_per_day,
   ]);
   echo $weeks;
   echo "lalala";
}
return;
?>
