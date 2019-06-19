<?php
require_once "connect.php";
 
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = $result =$checkbox_err=$recaptcha_err="";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Proszę podać nazwę użytkownika.";
    } 
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Nazwa jest już zajęta. Proszę wybrać inną.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Wystąpił błąd. Spróbuj ponownie poźniej.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    if(empty(trim($_POST["email"]))){
        $email_err = "Proszę podać mail.";
    } 
    else{
        $format = '/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9\-.]+\.[a-zA-Z]{2,6}$/';
        if(preg_match($format, trim($_POST["email"]))){
            $sql = "SELECT id FROM users WHERE email = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                $param_email = trim($_POST["email"]);
                
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "Adres mail jest już używany.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "Wystąpił błąd. Spróbuj ponownie poźniej.";
                }
            }
        }else{
            $email_err = "Błędny mail.";
        }
        mysqli_stmt_close($stmt);
    }
    if($_POST["checkbox"]==false){
        $checkbox_err="Musisz zaakceptować regulamin.";
    }
    $captcha;
    if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
    }
    if(!$captcha){
        $recaptcha_err = 'Potwierdź.';
    }
    $secretKey = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);

    if(empty(trim($_POST["password"]))){
        $password_err = "Proszę podać hasło.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Hasło musi mieć przynajmniej 6 znaków.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Proszę powtórzyć hasło.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Hasła się nie zgadzają.";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($checkbox_err) && empty($recaptcha_err)){
        
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            if(mysqli_stmt_execute($stmt)){
                $sql1 = "INSERT INTO progress (coin_lvl) VALUES (1)";
                mysqli_query($link, $sql1);

                $result = "Stworzono konto. Możesz się zalogować.";
            } else{
                $result = "Wystąpił błąd. Spróbuj ponownie poźniej.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.ico" />
    <title>Way To Fortune</title>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
</head>
<body>
    <div id="large_view">
        <header>
            <div id="title_png">
                <span style="display: inline-block;height: 100%;vertical-align: middle"></span><img src="images/title.png" alt="Error" style="vertical-align: middle;width:60%;height:70%;">
            </div>
        </header>
        <div id="border_horizontal"></div>
        <main>
            <div id="div_login">
                    <p class="font_5" style="text-align:center;font-weight:bold">Rejestracja</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="text-align:center;width:100%">
                    <p class="font_3" style="text-align:center;text-align:left;margin-left:10%">Login </p>
                    <input class="login_input" type="text" name="username" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?php echo $username; ?>"><br>
                    <p style="font-size:1.2em;color:red"><?php echo $username_err; ?></p>
                    <p class="font_3" style="text-align:center;text-align:left;margin-left:10%">Mail </p>
                    <input class="login_input" type="text" name="email" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?php echo $email; ?>"><br>
                    <p style="font-size:1.2em;color:red"><?php echo $email_err; ?></p>
                    <p class="font_3" style="text-align:center;text-align:left;margin-left:10%">Hasło </p>
                    <input class="login_input" type="password" name="password"><br>
                    <p style="font-size:1.2em;color:red"><?php echo $password_err; ?></p>    
                    <p class="font_3" style="text-align:center;text-align:left;margin-left:10%">Powtórz hasło </p>
                    <input class="login_input" type="password" name="confirm_password"><br>
                    <p style="font-size:1.2em;color:red"><?php echo $confirm_password_err; ?></p>
                    <br>
                    <p style="font-size:1.2em"><input type="checkbox" name="checkbox" > Akceptuję <a style="color:blue" href="regulation.php" target="_blank">regulamin.</a></p>
                    <p style="font-size:1.2em;color:red"><?php echo $checkbox_err; ?></p>
                    <br>
                    <div style="text-align:center"><div style="display: inline-block;" class="g-recaptcha" data-sitekey="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"></div></div>
                    <p style="font-size:1.2em;color:red"><?php echo $recaptcha_err; ?></p>
                    <input type="submit" value="Załóż konto" class="login_submit">
                    <p style="font-size:1.5em;color:#0CCA4A"><?php echo $result;?></p>
                </form> 
                <br>
                <p style="font-size:20px;text-align:center">Masz już konto? Wróc do strony logowania</p>
                <div style="text-align:center">
                    <input type="submit" value="POWRÓT" class="login_submit" onclick="window.location='index.php'">    
                </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
