<?php
//连接数据库
require 'connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//ErrorHandler
if (!$dbc) {
    die('Could not connect: '.mysqli_connect_error().'!');
}

$user_number = $_COOKIE['user_number'];
$dbt_name="user".$user_number;
$id=$_POST['historyid'];

$result=mysqli_query($dbc, "SELECT * FROM $dbt_name WHERE id='$id' ");
while ($row=mysqli_fetch_array($result)) {
    $number=$row['number'];
    $sytihao=explode("!", $number); 

    $geshu=count($sytihao);  
    for ($i=1;$i<$geshu;$i++) {
        echo ($i.".");//输出序号    
        //输出对应题与答案
        $tihao=$sytihao[$i];       
        $tihao=intval($tihao);
        $query2 = "SELECT * FROM tests WHERE id='$tihao'";
        $result2 = mysqli_query($dbc, $query2);
        $a_test=mysqli_fetch_array($result2);
        echo $a_test['question'].'<br/>';      
        echo '正确答案：'.$a_test['answer'].'<br/>';              
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

