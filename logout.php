<?php
//若 用户已登录，则 删除会话变量使他注销
session_start();
if (isset($_SESSION['user_id'])) {
    //通过将$_SESSION设置为一个空数组，来清除当前会话中的所有会话变量
    $_SESSION=array();
    // Delete the session cookie by setting its expiration to an hour ago (3600)
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600);
    }   
    session_destroy();    //结束会话
}

//删除用户ID和学号cookie
setcookie('user_id', '', time() - 3600);
setcookie('user_number', '', time() - 3600);

//重定向至主页
$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
header('Location: ' . $home_url);
?>
