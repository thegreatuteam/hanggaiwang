<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概测试结果</title>
    </head>
    <body>
     <h2>测试结果</h2>
<?php
//连接数据库
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//ErrorHandler
  if(!$dbc)
  {
   die('Could not connect: '.mysqli_connect_error().'!');
  }

//接收表单数据，判断正误并打分
    $score=0;
    $allnumber=$_POST["allnumber"];
    for($i=0; $i < $allnumber;$i++){
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
        if($char_num['num']==1){
            $user_answer=$_POST["$xuhao"];    
            if($a_test['answer'] == $user_answer){
                $score=$score+1;
                echo '<p>正确  ';
            }
            else{
                echo '<p>错误  ';        
            } 
        }
        //若 为多选题，正确+2分（全部正确才得分）
        else{      
            $useranswer=$_POST["$xuhao"]; 
            $user_answer=implode($useranswer);
            if($a_test['answer'] == $user_answer){
                $score=$score+2;
                echo '<p>正确  ';
            }
            else{
                echo '<p>错误  ';
            }       
        }      
        echo $xuhao.'. '.$a_test['question'].'<br/></p>';
        echo '<p>'.$a_test['a'].$a_test['b'].$a_test['c'].$a_test['d'].'</p>';
        echo '<p>你的答案是：'.$user_answer;
        echo '正确答案是：'.$a_test['answer'].'<br/></p>';        
    }  
    echo '<p>你的总得分是：'.$score.'分。</p>';

    mysqli_close($dbc);   //关闭数据库
?>   
  <p><a href="index.php">返回主页</a><br /></p>
      </body>
</html>
