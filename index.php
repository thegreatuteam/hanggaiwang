<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>伟大的航概网主页</title>
    </head>
   <body>
  
    <?php
   require_once('startsession.php');

  // 形成导航菜单
  if (isset($_SESSION['user_id'])) {
    echo ' <p><a href="select_chap.html">选择测试章节</a><br /><p>';
    echo ' <p><a href="logout.php">注销 (' . $_SESSION['user_number'] . ')</a><p>';
  }
  else {
    echo ' <p><a href="login.php">登录</a><br /><p>';
    echo ' <p><a href="signup.php">注册</a><p>';
  }
    ?>
   
   </body>
</html>
