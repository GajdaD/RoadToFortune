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

mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.ico" />
    <title>Way To Fortune</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            var id=<?php echo $_SESSION["id"]; ?>;
            var balance=<?php echo $balance;?>;
            var cost_lvlup=<?php echo pow(10,$coin_lvl);?>;
            var lvl_coin = <?php echo $coin_lvl;?>;

            if(balance<cost_lvlup){
                $("#coin_lvlup").prop('disabled', true);
            }
            else{
                $("#coin_lvlup").removeAttr("disabled");
            }

            $("#coin").click(function(){
                $("#coin_profit").text("+ " + lvl_coin);
                $("#coin_profit").fadeOut( "fast", function() {
                    $("#coin_profit").show();
                    $("#coin_profit").empty();
                });    
                
                $.ajax({
                        url:'coin.php',
                        method:'POST',
                        data:{
                            id:id,
                        },
                        success:function(response){
                            //console.log(response)
                            $("#balance").text(response);
                            balance=response;
                            cost_lvlup=<?php echo pow(10,$coin_lvl);?>;
                            if(balance<cost_lvlup){
                                $("#coin_lvlup").prop('disabled', true);
                            }
                            else{
                                $("#coin_lvlup").removeAttr("disabled");
                            }
                        }
                    });
            });
            $("#coin_lvlup").click(function(){
                $("#coin_lvlup").prop("onclick", null).off("click");
                $.ajax({
                        url:'coin_lvlup.php',
                        method:'POST',
                        data:{
                            id:id,
                    },
                        success:function(response){
                            location.reload();
                            //console.log(response)
                        }
                });
                


            });
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
            <div id="div_coin">
                <br>
                <img src="images/coin.png" alt="Error" id="coin">
            </div>
            <div id="div_coin_lvlup">
                <p class="font_4">Ulepszanie monety</p>
                <p class="font_2">Twój poziom - <?php echo $coin_lvl; ?></p>
                <br>
                <p class="font_4">Następny poziom</p>
                <button id="coin_lvlup" class="header_click"><p class="font_5"><?php echo pow(10,$coin_lvl);?>$</p></button>
            </div>
            <div id="game_footer">
                <table id="footer_table"> 
                    <tr class="footer_top_border"></tr>
                    <tr class="footer_main">
                        <td class="footer_border"></td>
                        <td class="footer_p footer_click" onclick="window.location='wheel_of_fortune.php'"><p>KOŁO FORTUNY</p></td>
                        <td class="footer_border"></td>
                        <td class="footer_p footer_click" onclick="window.location='ranking.php'"><p>RANKING</p></td>
                        <td class="footer_border"></td>
                    </tr>
                    <tr class="footer_top_border"></tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>