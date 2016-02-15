<?php
    
session_start();

// 若 会话变量没有被设置,则 尝试用cookie来设置会话变量
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_number'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['user_number'] = $_COOKIE['user_number'];
    }
}
?>
