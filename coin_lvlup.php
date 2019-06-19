<?php
require_once "connect.php";

$id=$_POST["id"];
$coin_lvl=1;

$sql = "SELECT coin_lvl FROM progress WHERE id =$id";
if ($result1 = mysqli_query($link, $sql)) {
    $row1 = mysqli_fetch_assoc($result1);
    $coin_lvl = $row1['coin_lvl'];
    mysqli_free_result($result1);
}
$cost_lvl = pow(10,$coin_lvl);

$sql1="UPDATE users SET balance = balance - $cost_lvl WHERE id =$id";
mysqli_query($link, $sql1);

$sql2="UPDATE progress SET coin_lvl = coin_lvl + 1 WHERE id =$id";
mysqli_query($link, $sql2);

mysqli_close($link);
?>