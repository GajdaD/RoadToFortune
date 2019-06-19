<?php
require_once "connect.php";

$id=$_POST["id"];
$stake=$_POST["stake"];

$sql1="UPDATE users SET balance = balance - $stake WHERE id =$id";
mysqli_query($link, $sql1);

$rand = mt_rand(0, 359);

if($rand<=45){
    //echo "2";
    $profit=2*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=90 && $rand>45){
    //echo "1";
    $profit=1*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=135 && $rand>90){
    //echo "0";
    $profit=0*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=180 && $rand>135){
    //echo "1";
    $profit=1*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=225 && $rand>180){
    //echo "2";
    $profit=2*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=270 && $rand>225){
    //echo "5";
    $profit=5*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=315 && $rand>270){
    //echo "0";
    $profit=0*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else if($rand<=359 && $rand>315){
    //echo "1";
    $profit=1*$stake;
    $sql2="UPDATE users SET balance = balance + $profit WHERE id =$id";
    mysqli_query($link, $sql2);
}
else{
    //Error
}

echo $rand;
?>