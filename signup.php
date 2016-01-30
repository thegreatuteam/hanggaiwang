<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概网注册界面</title>
        <style>
          body {
    color: #8aa4ae;
    background-image: url("image/main.png");
    background-attachment: fixed;
    margin-top: 7%;
    margin-left: 20%;
	margin-right: 20%;
	padding: 50px 50px 50px 50px;
    text-align: center;
	font-family: 'Microsoft JhengHei UI';
    font-size: 30px;
    font-weight: bold;
         }	
       </style>
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
 
    $okregister='1';   //$okregister初始值为1，若最终为1，则判定用户数据合理；若有不合理之处，变为0
 
    //若 有表单项为空，则 $okregister值变为0
    if (empty($user_number) || empty($password1) || empty($password2)) {
        $okregister='0';
        echo '<p class="error">--请将注册信息全部填满！--</p>';
    }
    //若 两次密码输入不一致，则 $okregister值变为0
    if ($password1!== $password2) {
        $okregister='0';
        echo '<p class="error">--请保证两次密码填写一致！--</p>';
    }
    //确保学号为8位数字--即保证是北航学号啦~ 用正则表达式判断
    if(!preg_match('/^\d{8}$/',$user_number)){
        $okregister='0'; 
        echo '<p class="error">--请输入有效的学号，8位数字哟！--</p>'; 
        $user_number = "";
    }
     //确保密码为6-12位且仅由数字或字母组成 用正则表达式判断
    if(!preg_match('/^\w{6,12}$/',$password1)){
        $okregister='0'; 
        echo '<p class="error">--请确保密码为6-12位且仅由数字或字母组成--</p>'; 
        $password1="";
        $password2="";
    }  
    //确保没有人已经注册了该学号
    $query = "SELECT * FROM users WHERE user_number = '$user_number'";
    $data = mysqli_query($dbc, $query);
    if (mysqli_num_rows($data) !== 0) {
        $okregister='0';
        echo '<p class="error">--糟糕，这个学号已经被注册过了！请使用未被注册过的学号注册。--</p>';
        $user_number = "";
    }
 
    //最终，若$okregister值为1（即PHP判定用户数据合理）,则 将注册数据插入数据库表
    if($okregister=='1'){
        $query = "INSERT INTO users (user_number, password, join_date) VALUES ('$user_number', SHA('$password1'), NOW())";
        mysqli_query($dbc, $query);

        //为新注册的用户创建一个新的独立的数据库表 [我写的————by李嘉锟]
        $dbt_name="user".$user_number;   // 定义数据库表名称 
        $sql = "CREATE TABLE $dbt_name (
        id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
        time text,
        score text,
        number text
        )";
        mysqli_query($dbc,$sql);
        
        // 向用户证实已成功注册
        echo '<p>--恭喜'.$user_number.'~ 你已经注册成功~<br/>现在请<a href="login.php">登录</a>吧。--</p>';

        mysqli_close($dbc);
        exit();
      }
  }
  mysqli_close($dbc);
?>

  <p>请输入你的8位学号，并两次输入以确认密码。<br/>并请确保密码为6-12位且仅由数字或字母组成。</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    
      <label for="number">学号:</label>
      <input type="text" id="number" name="number" value="<?php if (!empty($user_number)) echo $user_number; ?>" /><br />
      <label for="password1">密码:</label>
      <input type="password" id="password1" name="password1" /><br />
      <label for="password2">再次输入密码:</label>
      <input type="password" id="password2" name="password2" /><br />
    
    <input type="submit" value="注册" name="submit" />
  </form>

  <p><a href="index.php">返回主页</a><br/></p>

    </body>
</html>
