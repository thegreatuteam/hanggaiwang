<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概网注册界面</title>
    </head>
    <body>
      <h3>注册</h3>

<?php
  require_once('connectvars.php');

  // 连接数据库
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // 从表单获取注册数据
    $user_number = mysqli_real_escape_string($dbc, trim($_POST['number']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if (!empty($user_number) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {
      //  确保没有人已经注册了该学号
      $query = "SELECT * FROM users WHERE user_number = '$user_number'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        // 该用户学号是唯一的,则 将注册数据插入数据库表
        $query = "INSERT INTO users (user_number, password, join_date) VALUES ('$user_number', SHA('$password1'), NOW())";
        mysqli_query($dbc, $query);

        // 向用户证实已成功注册
        echo '<p>--恭喜你啊~注册成功了~现在请<a href="login.php">登录</a>吧。--</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        // 若 该学号已被注册，则 显示错误信息
        echo '<p class="error">--抱歉啊，这个学号已经被注册过了...请使用未被注册过的学号注册。--</p>';
        $user_number = "";
      }
    }
    else {
      echo '<p class="error">--必须要将注册信息全部填满 且 保证两次密码填写一致哦！--</p>';
    }
  }

  mysqli_close($dbc);
?>

  <p>请输入你的学号，并两次输入密码。</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    
      <label for="number">学号:</label>
      <input type="text" id="number" name="number" value="<?php if (!empty($user_number)) echo $user_number; ?>" /><br />
      <label for="password1">密码:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">再次输入密码:</label>
      <input type="password" id="password2" name="password2" /><br />
    
    <input type="submit" value="注册" name="submit" />
  </form>
  
    </body>
</html>
