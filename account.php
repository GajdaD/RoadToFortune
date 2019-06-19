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
 
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Podaj nowe hasło.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Hasło musi mieć przynajmniej 6 znaków.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Powtórz hasło.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Hasła są różne.";
        }
    }
        
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: index.php");
                exit();
            } else{
                echo "Spróbuj ponownie później.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
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
                        <td class="header_p"><p>KONTO</p></td>
                        <td class="header_border"></td>
                        <td class="header_p header_click" onclick="window.location='logout.php'"><p>WYLOGUJ</p></td>
                        <td class="header_border"></td>
                    </tr>
                    <tr class="header_top_border"></tr>
                </table>
            </div>
            <div id="div_acc">
                <br>
                <p class="font_5" style="text-align:center">Zresetuj hasło</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <p class="font_4">Nowe hasło</p>
                        <input type="password" name="new_password" class="login_input" value="<?php echo $new_password; ?>">
                        <p class="font_2" style="color:red"><?php echo $new_password_err; ?></p>
                        <br>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <p class="font_4">Powtórz hasło</p>
                        <input type="password" name="confirm_password" class="login_input">
                        <p class="font_2" style="color:red"><?php echo $confirm_password_err; ?></p>
                        <br>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="login_submit" value="ZMIEŃ">
                    </div>
                </form>
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