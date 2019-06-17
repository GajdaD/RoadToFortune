<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: game.php");
    exit;
}

require_once 'connect.php';

$username = $password = "";
$username_err = $password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = 'Wpisz login.';
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST['password']))){
        $password_err = 'Wpisz hasło.';
    } else{
        $password = trim($_POST['password']);
    }
    
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: game.php");
                        } else{
                            $password_err = 'Złe hasło.';
                        }
                    }
                } else{
                    $username_err = 'Nie ma konta o tym loginie.';
                }
            } else{
                echo "Wystąpił błąd. Spróbuj ponownie później.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
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
                <div id="div_guest">
                    <p class="font_5" style="font-weight:bold">Graj jako gość</p>
                    <br>
                    <br>
                    <p class="font_2">Jeśli nie chcesz zakładać konta<br> możesz skorzystać z publicznego konta.</p>
                    <br>
                    <p class="font_2">Login : <b>guest1</b></p>
                    <p class="font_2">Hasło : <b>guest1</b></p>
                </div>
                <div id="border_vertical"></div>
                <div id="div_log_reg">
                    <p class="font_5" style="text-align:center;font-weight:bold">Zaloguj się</p>
                    <br>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="text-align:center;width:100%">
                        <p class="font_2" style="text-align:center;text-align:left;margin-left:10%">Login </p>
                        <input class="login_input" type="text" name="username" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="<?php echo $username; ?>"><br>
                        <p style="font-size:1em;color:red"><?php echo $username_err; ?></p>
                        <br>
                        <p class="font_2" style="text-align:center;text-align:left;margin-left:10%">Hasło </p>
                        <input class="login_input" type="password" name="password"><br>
                        <p style="font-size:1em;color:red"><?php echo $password_err; ?></p>
                        <br>        
                        <input type="submit" value="ZALOGUJ" class="login_submit">
                    </form>
                    <br>
                    <p class="font_2" style="text-align:center">Nie masz konta? Zarejestruj się </p>
                    <br>
                    <div style="text-align:center">
                    <input type="submit" value="REJESTRACJA" class="login_submit" onclick="window.location='register.php'">    
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
