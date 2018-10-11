<?php
	session_start(); 
	header("Content-type: text/html; charset=utf-8");
	require_once '../format/.conf.ini';

	if(isset($_SESSION["id"])){  //使用session作为保护
		if((int)($_SESSION["privilege"])>0)
			return ;
		
		$conn = mysqli_connect($DBHOST,$DBUSER,$DBPWD,$DBNAME);
		//标志位设置为1, 代表允许选班操作
		$sql="update flags set flag_value = 1 where flag_name = 'allow_shift';";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
	}
?>