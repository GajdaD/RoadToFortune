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
            var active=0;
            if(balance>=100){
                $("#p_100").css("background-color","#0CCA4A");
                $("#p_100").css("cursor","pointer");
                $("#p_100").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=100;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_100").css("opacity","1");
                }); 
            }
            if(balance>=1000){
                $("#p_1000").css("background-color","#0CCA4A");
                $("#p_1000").css("cursor","pointer");
                $("#p_1000").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=1000;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_1000").css("opacity","1");
                });
            }
            if(balance>=10000){
                $("#p_10000").css("background-color","#0CCA4A");
                $("#p_10000").css("cursor","pointer");
                $("#p_10000").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=10000;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_10000").css("opacity","1");
                });
            }
            if(balance>=100000){
                $("#p_100000").css("background-color","#0CCA4A");
                $("#p_100000").css("cursor","pointer");
                $("#p_100000").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=100000;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_100000").css("opacity","1");
                }); 
            }
            if(balance>=1000000){
                $("#p_1000000").css("background-color","#0CCA4A");
                $("#p_1000000").css("cursor","pointer");
                $("#p_1000000").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=1000000;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_1000000").css("opacity","1");
                }); 
            }
            if(balance>=10000000){
                $("#p_10000000").css("background-color","#0CCA4A");
                $("#p_10000000").css("cursor","pointer");
                $("#p_10000000").click(function(){ 
                    $("#spin").css("background-color","#0CCA4A");
                    $("#spin").css("cursor","pointer");
                    active=10000000;
                    $("#stake_p").text(active + " $");
                    $(".low_opacity").css("opacity","0.6");
                    $("#p_10000000").css("opacity","1");
                }); 
            }
            $("#spin").click(function(){
                if(active!=0){
                    $.ajax({
                        url:'spin.php',
                        method:'POST',
                        data:{
                            id:id,
                            stake:active,
                        },
                        success:function(response){
                            $("#spin").prop("onclick", null).off("click");
                            $("#spin").css("background-color","#6E8387");
                            $("#spin").css("cursor","default");
                            var rotate = (-1 * response) - 360 ;
                            $("#wheel_img").animate(
                                { deg: rotate },
                                {
                                    duration: 1200,
                                    step: function(now) {
                                        $(this).css({ transform: 'rotate(' + now + 'deg)' });
                                    }
                                }
                            );

                            if(response<=45){
                                //echo "2";
                                setTimeout(function() {
                                    $("#p_profit").text("+ " + active);
                                }, 1200);
                            }
                            else if(response<=90 && response>45){
                                //echo "1";
                                setTimeout(function() {
                                    $("#p_profit").text("+ 0");
                                }, 1200);
                            }
                            else if(response<=135 && response>90){
                                //echo "0";
                                setTimeout(function() {
                                    $("#p_profit").text("- " + active);
                                }, 1200);
                            }
                            else if(response<=180 && response>135){
                                //echo "1";
                                setTimeout(function() {
                                    $("#p_profit").text("+ 0");
                                }, 1200);
                            }
                            else if(response<=225 && response>180){
                                //echo "2";
                                setTimeout(function() {
                                    $("#p_profit").text("+ " + active);
                                }, 1200);
                            }
                            else if(response<=270 && response>225){
                                //echo "5";
                                setTimeout(function() {
                                    $("#p_profit").text("+ " + 4*active);
                                }, 1200);
                            }
                            else if(response<=315 && response>270){
                                //echo "0";
                                setTimeout(function() {
                                    $("#p_profit").text("- " + active);
                                }, 1200);
                            }
                            else if(response<=359 && response>315){
                                //echo "1";
                                setTimeout(function() {
                                    $("#p_profit").text("+ 0");
                                }, 1200);
                            }
                            else{
                                //Error
                            }
                            window.setTimeout(function(){window.location.reload()}, 2000);
                        }
                });
                } 
                
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
                        <td class="header_p"><p><span id="balance"><?php echo $balance; ?></span>$</p></td>
                        <td class="header_border"></td>
                        <td class="header_p header_click" onclick="window.location='account.php'"><p>KONTO</p></td>
                        <td class="header_border"></td>
                        <td class="header_p header_click" onclick="window.location='logout.php'"><p>WYLOGUJ</p></td>
                        <td class="header_border"></td>
                    </tr>
                    <tr class="header_top_border"></tr>
                </table>
            </div>
            <div id="wof">
                <div id="wheel">
                    <br>
                    <div id="wheel_div">
                        <img id="wheel_img" src="images/wheel.png" alt="Error">
                        <img id="block_1" src="images/triangle.png" alt="Error">
                        <br>
                        <p id="spin" class="font_4">ZAKRĘĆ</p>
                        <p id="p_profit" class="font_4" style="color:#0CCA4A;text-align:center"></p>
                    </div>
                </div>
                <div id="tickets">
                    <p class="font_4">WYBIERZ STAWKĘ</p>
                    <br>
                    <p id="p_100" class="font_2 wof_click low_opacity">100$</p>
                    <br>
                    <p id="p_1000" class="font_2 wof_click low_opacity">1 000$</p>
                    <br>
                    <p id="p_10000" class="font_2 wof_click low_opacity">10 000$</p>
                    <br>
                    <p id="p_100000" class="font_2 wof_click low_opacity">100 000$</p>
                    <br>
                    <p id="p_1000000" class="font_2 wof_click low_opacity">1 000 000$</p>
                    <br>
                    <p id="p_10000000" class="font_2 wof_click low_opacity">10 000 000$</p>
                    <br>
                    <p class="font_4">STAWKA</p>
                    <p id="stake_p" class="font_2">0</p>
                </div>
                
            </div>
            <div id="game_footer">
                <table id="footer_table"> 
                    <tr class="footer_top_border"></tr>
                    <tr class="footer_main">
                        <td class="footer_border"></td>
                        <td class="footer_p footer_click" onclick="window.location='game.php'"><p>MONETA</p></td>
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