
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>个人历史成绩查询</title>
    </head>
    <body>

<?php
//连接数据库
require 'connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//ErrorHandler
if (!$dbc) {
    die('Could not connect: '.mysqli_connect_error().'!');
}
$i=0;
echo '<h2>' .$_COOKIE['user_number'] . '的历史记录</h2>';

//查询该用户数据库表中记录总条数
$user_number = $_COOKIE['user_number'];
$dbt_name="user".$user_number;

//循环输出每条记录 测试时间，得分，历史记录的超链接
$result=mysqli_query($dbc, "SELECT * FROM $dbt_name");
while ($row=mysqli_fetch_array($result)) {   
    $testtime=date("Y-m-d H:i:s", $row['time']);
    $score=$row['score'];
    $i=$i+1;
    echo "<form method='post' action='history.php'>";
    echo "日期".$testtime."&#8194;&#8194;得分".$score;
    echo "<input type='hidden' name='historyid' id='historyid' value='";
    echo $i;
    echo "'>";
    echo "<input type='submit' value='历史错题' />";
    echo "</form>"; 
}
?>


<p><a href="index.php">返回主页</a><br/></p>


    </body>
</html>
