<?php
	session_start(); 
	unset($_SESSION['id']);  //删除在session中注册的用户名变量
	unset($_SESSION['password']); 
	unset($_SESSION['privilege']);  
	session_destroy();
	header('Location:index.php');
?>