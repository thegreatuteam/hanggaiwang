<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>航概测试页面</title>
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
       </style>
    </head>
    <body>
<h2>来测试航概吧 &#10084</h2>     
<?php

//经左同学改良的函数 随机选题号不重复，且使题号对应分数相加为定值
function myRand($min, $max, $num, $score, $scores) {
    $tag[1]=$num*2-$score;
    $tag[2]=$num-$tag[1];
    $count=0;
    $return=array();
    while ($count<$num and $score!=0) {
        $rnd=mt_rand($min, $max);
        $return[]=$rnd;
        array_unique($return);
        if ($count!=count($return)) {
            if (!$scores[$rnd]) {
                array_pop($return);
            } elseif ($tag[$scores[$rnd]]==0) {
                $tmp=$scores[$rnd];
                foreach ($scores as $key=>$value) {
                    if ($value==$tmp) {
                        unset($scores[$key]);
                    }
                }
                array_pop($return);
            } else {
                $count++;
                $score-=$scores[$rnd];
                $tag[$scores[$rnd]]--;
                unset($scores[$rnd]);
            }
        }
    }
    shuffle($return);
    return $return;
}
//根据选择的章节确定题号范围
/*if ($_POST['chap']=='c1'){
    $min=1;
    $max=10;
}
if ($_POST['chap']=='c2'){
    $min=11;
    $max=20;
}
if ($_POST['chap']=='c3'){
    $min=21;
    $max=30;
}
if ($_POST['chap']=='c4'){
    $min=31;
    $max=40;
}
if ($_POST['chap']=='c5'){
    $min=41;
    $max=50;
}
if ($_POST['chap']=='ca'){
    $min=1;
    $max=50;
}
*/

switch ($_POST['chap'])
{
case 'c1':
    $min=1;
    $max=10;
    break;
case 'c2':
    $min=11;
    $max=20;
    break;
case 'c3':
    $min=21;
    $max=30;
    break;
case 'c4':
    $min=31;
    $max=40;
    break;
case 'c5':
    $min=41;
    $max=50;
    break;
case 'ca':
    $min=1;
    $max=50;
    break;
default:
    $min=1;
    $max=50;
}


//连接数据库
require 'connectvars.php';
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//ErrorHandler
if(!$dbc){
    die('Could not connect: '.mysqli_connect_error().'!');
}

//查询
$query0="SELECT * FROM tests";
$result0=mysqli_query($dbc, $query0); 

while ($mysql_row=mysqli_fetch_array($result0)) {
    $scores[(int)$mysql_row['number']]=(int)$mysql_row['score'];    //获取题号与对应分数
}  

//使用MyRand($min, $max, $num, $score, $scores)函数随机选取题号不重复
$num=mt_rand(4, 7);
$score=7;
$test_num=myRand($min, $max, $num, $score, $scores);
sort($test_num);

//构建“卷子”表单
$each_num= '';
echo('<form action="cookanswer.php" method="post">');
for ($i=0;$i<count($test_num);$i++) {
    $each_num= $test_num[$i]; 
    $query1 = "SELECT * FROM tests WHERE number='$each_num'";
    $result1 = mysqli_query($dbc, $query1);
    $a_test=mysqli_fetch_array($result1);
    $xuhao=$i+1;
    $id=$a_test['id'];
    $query2 = "SELECT CHAR_LENGTH(answer) AS num FROM tests WHERE id='$id'";   //查询answer字符数，从而判断是单选or多选题
    $result2=mysqli_query($dbc, $query2);
    $char_num=mysqli_fetch_array($result2);
 
    echo '<p>'.$xuhao.'. '.$a_test['question']."<br/></p>";
    //若 为单选，则 构造单选按钮
    if ($char_num['num']==1) {
        echo('<p><input type="radio" name="'.$xuhao.'" value="A">'.$a_test['a'].
            '<input type="radio" name="'.$xuhao.'" value="B">'.$a_test['b'].
            '<input type="radio" name="'.$xuhao.'" value="C">'.$a_test['c'].
            '<input type="radio" name="'.$xuhao.'" value="D">'.$a_test['d'].'</p>');
        echo('<input type="hidden" name="a'.$xuhao.'" value="'.$a_test['id'].'">');
    } else {    //若 为多选，则 构造复选框
        echo('<p><input type="checkbox" name="'.$xuhao.'[]" value="A">'.$a_test['a'].
            '<input type="checkbox" name="'.$xuhao.'[]" value="B">'.$a_test['b'].
            '<input type="checkbox" name="'.$xuhao.'[]" value="C">'.$a_test['c'].
            '<input type="checkbox" name="'.$xuhao.'[]" value="D">'.$a_test['d'].'</p>');
        echo('<input type="hidden" name="a'.$xuhao.'" value="'.$id.'">');
    }
}
echo('<input type="hidden" name="allnumber" value="'.count($test_num).'">');
echo('<input type="submit" name="submit" value="交卷"></form>'); 
mysqli_close($dbc);  //关闭数据库
?>
 
<!-- <p><a href="test.php">重新选题</a></p> -->
<p><a href="select_chap.html">重新选择测试章节</a><br /></p>
<p><a href="index.php">返回主页</a><br /></p>
              
    </body>
</html>