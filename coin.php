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

$sql1="UPDATE users SET balance = balance + $coin_lvl WHERE id =$id";
mysqli_query($link, $sql1);
$sql2 = "SELECT balance FROM users WHERE id =$id";
if ($result = mysqli_query($link, $sql2)) {
    $row = mysqli_fetch_assoc($result);
    echo $row["balance"];
    mysqli_free_result($result);
}
mysqli_close($link);
?>