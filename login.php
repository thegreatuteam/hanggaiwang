
   
   <?php
  require_once('connectvars.php');

  // 开始会话
  session_start();

  // 清除错误信息
  $error_msg = "";

  // 若 用户未登录，则 尝试将他们登录
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      // 连接数据库
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      // 获取用户登录数据
      $user_number = mysqli_real_escape_string($dbc, trim($_POST['number']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_number) && !empty($user_password)) {
        //在数据库中查找用户ID和学号（如果用户学号与密码与数据库中的匹配时）
        $query = "SELECT user_id, user_number FROM users WHERE user_number = '$user_number' AND password = SHA('$user_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // 可以登录，于是将 用户ID和用户学号 设置会话和cookie变量，并重定向至主页
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['user_number'] = $row['user_number'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 7));    // 于7天失效
          setcookie('user_number', $row['user_number'], time() + (60 * 60 * 24 * 7));  // 于7天失效
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // 用户学号或密码错误，显示错误信息
          $error_msg = '对不起，你的学号或密码不对呢...';
        }
      }
      else {
        // 用户未输入学号或密码，显示错误信息
        $error_msg = '对不起，学号和密码不能为空哦~';
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概网登录界面</title>
       <style>
          body {
    color: #8aa4ae;
    background-image: url("image/main.png");
    background-attachment: fixed;
    margin-top: 10%;
    margin-left: 20%;
	margin-right: 20%;
	padding: 50px 50px 50px 50px;
    text-align: center;
	font-family: 'Microsoft JhengHei UI';
    font-size: 40px;
    font-weight: bold;
         }	
       </style>
    </head>
    <body>
    <h3>登录</h3>

<?php
  // 若 会话变量为空，显示错误信息和登录表单；否则，证实该用户已登录 
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   
      <label for="number">学号:</label>
      <input type="text" name="number" value="<?php if (!empty($user_number)) echo $user_number; ?>" /><br />
      <label for="password">密码:</label>
      <input type="password" name="password" />
   
    <input type="submit" value="登录" name="submit" />
  </form>

  <p><a href="index.php">返回主页</a><br/></p>

<?php
  }
  else {
    // 证实该用户已成功登录
    echo('<p class="login">--' . $_SESSION['user_number'] . '你已成功登录~<br/>快去做测试吧~--</p>');
  }
?>
     
             
    </body>
</html>
