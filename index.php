<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概网主页</title>
        <style>
          body {
    color: #8aa4ae;
    background-image: url("image/main.png");
    background-attachment: fixed;
    margin-top: 13%;
    margin-left: 20%;
	margin-right: 20%;
	padding: 50px 50px 50px 50px;
    text-align: center;
	font-family: 'Microsoft JhengHei UI';
    font-size: 50px;
    font-weight: bold;
         }	
        </style>
    </head>
   <body>
  
<?php
require 'startsession.php';

// 形成导航菜单
if (isset($_SESSION['user_id'])) {
    echo ' <p><a href="select_chap.html">选择测试章节</a><br /><p>';
    echo ' <p><a href="logout.php">注销 (' . $_SESSION['user_number'] . ')</a><p>';
} else {
    echo ' <p><a href="login.php">登录</a><br /><p>';
    echo ' <p><a href="signup.php">注册</a><p>';
}
?>
   
   </body>
</html>
