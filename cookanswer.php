<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概测试结果</title>
        <style type="text/css">
         body {
            color: #15075e;
            background-image: url("image/1.png");
            background-attachment: fixed;
            margin-top: 13%;
            margin-left: 20%;
	        margin-right: 20%;
	        padding: 50px 50px 50px 50px;           
	        font-family: 楷体;
            font-size: 30px;
         }	
          p.right{
                color: #0a7c36;
         }  
          p.wrong{
                color: #8b0a0a;
         }
     </style>
    </head>
    <body>
     <h2>测试结果</h2>
<?php
$number="";    //保存错误题号 

//连接数据库
require 'connectvars.php';
$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//ErrorHandler
if (!$dbc) {
    die('Could not connect: '.mysqli_connect_error().'!');
}

//接收表单数据，判断正误并打分
$score=0;
$allnumber=$_POST["allnumber"];
for ($i=0;$i<$allnumber;$i++) {
    $xuhao=$i+1;      
    $a_id=$_POST["a$xuhao"];
    $query = "SELECT * FROM tests WHERE id='$a_id'";
    $result = mysqli_query($dbc, $query);
    $a_test=mysqli_fetch_array($result);
    //查询answer字符数，从而判断是单选or多选题
    $query2 = "SELECT CHAR_LENGTH(answer) AS num FROM tests WHERE id='$a_id'";
    $result2=mysqli_query($dbc, $query2);
    $char_num=mysqli_fetch_array($result2);

    //若 为单选题，正确+1分
    if ($char_num['num']==1) {
        $user_answer=$_POST["$xuhao"];    
        if ($a_test['answer'] == $user_answer) {
            $score=$score+1;
            echo '<p class="right">正确  ';
        } else {
            echo '<p class="wrong">错误  ';
            $number=$number."!".$a_id;        
        } 
    } else {    //若 为多选题，正确+2分（全部正确才得分）
        $useranswer=$_POST["$xuhao"]; 
        $user_answer=implode($useranswer);
        if ($a_test['answer'] == $user_answer) {
            $score=$score+2;
            echo '<p class="right">正确  ';
        } else {
            echo '<p class="wrong">错误  ';
            $number=$number."!".$a_id;
        }       
    }      
    echo $xuhao.'. '.$a_test['question'].'<br/></p>';   //！attention 题目颜色待改
    echo '<p>'.$a_test['a'].'   '.$a_test['b'].'&nbsp&nbsp&nbsp&nbsp&nbsp'.$a_test['c'].'    '.$a_test['d'].'</p>';   //！attention 选项间距待调整
    echo '<p>你的答案是：'.$user_answer;
    echo '正确答案是：'.$a_test['answer'].'<br/></p>';        
}  
echo '<p>你的总得分是：'.$score.'分。</p>';

//此以下PHP由李嘉锟编写
//将用户该次成绩记录插入该用户的数据库表
$dbt_name="user".$_COOKIE["user_number"];
mysqli_query($dbc, "INSERT INTO $dbt_name (time, score, number) VALUES (UNIX_TIMESTAMP(), '$score', '$number')");

mysqli_close($dbc);   //关闭数据库   
?>  


  <p><a href="getscore.php">查询你的成绩记录</a><br/></p>
  <p><a href="select_chap.html">重新选择测试章节</a><br/></p>       
  <p><a href="index.php">返回主页</a><br/></p>
    </body>
</html>
