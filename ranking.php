<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "connect.php";
$sql = "SELECT balance FROM users WHERE id =".$_SESSION["id"];
if ($result = mysqli_query($link, $sql)) {
    $row = mysqli_fetch_assoc($result);
    $balance = $row['balance'];
    mysqli_free_result($result);
}
$sql1 = "SELECT coin_lvl FROM progress WHERE id =".$_SESSION["id"];
if ($result1 = mysqli_query($link, $sql1)) {
    $row1 = mysqli_fetch_assoc($result1);
    $coin_lvl = $row1['coin_lvl'];
    mysqli_free_result($result1);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Way To Fortune</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           
        });
    </script>
</head>
<body>
    <div id="large_view">
        <div id="game">
            <div id="game_header">
                <table id="header_table"> 
                    <tr class="header_top_border"></tr>
                    <tr class="header_main">
                        <td class="header_border"></td>
                        <td class="header_p"><p><?php echo $_SESSION["username"]; ?></p></td>
                        <td class="header_border"></td>
                        <td class="header_p"><p><span id="balance"><?php echo $balance; ?></span>$<p id="coin_profit" class="font_3" style="position:absolute;color:#0CCA4A;text-align:center"></p></p></td>
                        <td class="header_border"></td>
                        <td class="header_p header_click" onclick="window.location='account.php'"><p>KONTO</p></td>
                        <td class="header_border"></td>
                        <td class="header_p header_click" onclick="window.location='logout.php'"><p>WYLOGUJ</p></td>
                        <td class="header_border"></td>
                    </tr>
                    <tr class="header_top_border"></tr>
                </table>
            </div>
            <div id="div_rank">
                <p class="font_5">RANKING</p>
                <div id="rank_table">
                    <?php
                    $sql2 = "SELECT * FROM users WHERE 1 ORDER BY users.balance DESC LIMIT 0 , 10"; 
                    if ($result2 = mysqli_query($link, $sql2)) {
                        $i=1;
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            if($i==1){
                                echo ('<div class="rank_row" style="background-color:#D4AF37"><p class="font_5">'.$i." | ".$row2["username"]." | ".$row2["balance"].'</p></div>');
                            }
                            else if($i==2){
                                echo ('<div class="rank_row" style="background-color:#C0C0C0"><p class="font_4">'.$i." | ".$row2["username"]." | ".$row2["balance"].'</p></div>');
                            }
                            else if($i==3){
                                echo ('<div class="rank_row" style="background-color:#CD7F32"><p class="font_4">'.$i." | ".$row2["username"]." | ".$row2["balance"].'</p></div>');
                            }
                            else{
                                echo ('<div class="rank_row"><p class="font_3">'.$i." | ".$row2["username"]." | ".$row2["balance"].'</p></div>');
                            }
                            $i++;
                        }
                        mysqli_free_result($result2);
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </div>
            <div id="game_footer">
                <table id="footer_table"> 
                    <tr class="footer_top_border"></tr>
                    <tr class="footer_main">
                        <td class="footer_border"></td>
                        <td class="footer_p footer_click" onclick="window.location='game.php'"><p>MONETA</p></td>
                        <td class="footer_border"></td>
                        <td class="footer_p footer_click" onclick="window.location='wheel_of_fortune.php'"><p>KOŁO FORTUNY</p></td>
                        <td class="footer_border"></td>
                    </tr>
                    <tr class="footer_top_border"></tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>