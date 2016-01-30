<?php
//连接数据库
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//ErrorHandler
if(!$dbc) {
  die('Could not connect: '.mysqli_connect_error().'!');
}

//以下获取题号的功能是李嘉锟编写的哟~
$user_number = $_COOKIE['user_number'];
$dbt_name="user".$user_number;
$id=$_POST['historyid'];

$result=mysqli_query($dbc,"SELECT * FROM $dbt_name WHERE id='$id' ");
while ($row=mysqli_fetch_array($result)) {
    $number=$row['number'];
    $times=strlen($number)/2;
    for ($i=1;$i<=$times;$i++) {
        echo $i.".";//输出序号
        $left=strpos($number,"!");
        $right=strpos(substr($number,strpos($number,"!")+1),"!");
            if ($right) {
            $tihao=substr($number,strpos($number,"!")+1,$right-$left);    //获取题号       
            //输出对应题与答案
            $query2 = "SELECT * FROM tests WHERE id='$tihao'";
            $result2 = mysqli_query($dbc, $query2);
            $a_test=mysqli_fetch_array($result2);
            echo $a_test['question'].'<br/>';      
            echo '正确答案：'.$a_test['answer'].'<br/>';        
      
        }
        else {
            $tihao=substr($number,strpos($number,"!")+1);    //获取题号      
            $query2 = "SELECT * FROM tests WHERE id='$tihao'";
            $result2 = mysqli_query($dbc, $query2);
            $a_test=mysqli_fetch_array($result2);
            echo $a_test['question'].'<br/>';      
            echo '正确答案：'.$a_test['answer'].'<br/>';
        }
        $number=substr($number,strpos($number,"!")+1+$right-$left);
        
    }
}

echo('<a href="getscore.php">返回个人历史记录查询页面</a><br/>');
echo('<a href="select_chap.html">前往选择测试章节页面</a><br/>');
echo('<a href="index.php">返回主页</a>');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>历史错题记录</title>
    </head>
    <body>
        
    </body>
</html>
