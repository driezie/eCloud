<?php
// Verbinding met de database folder
require_once './actions/db/db_connect.php';
$dbh = getDB();


if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user) {
        $alert = "Account met deze gebruikersnaam bestaat al.";
    } else {
        // check if email is from @outlook.com
        if (strpos($email, '@outlook.com') !== false) {

            $alert = "Outlook emails zijn op dit moment niet mogelijk om mee in te loggen. Probeer een andere email.";
        }   else {
            if ($password == $password2) {
            
                $verified_code = rand(1, 999999999);
                $folder_id = rand(1, 999999999);
    
                // hash password
                $password = password_hash($password, PASSWORD_DEFAULT);
                //remove everything after @ from email
                $displayname = explode('@', $email);
                $displayname = $email[0];
                // create displayname


                // check if checkmark newsletter is checked
                if (isset($_POST['newsletter'])) {
                    $newsletter = 'Y';
                } else {
                    $newsletter = 'N';
                }
                // set current date into $account_created
                $account_created = date('Y-m-d');
                // set verified to N

    
                $stmt = $dbh->prepare("INSERT INTO users (email, password, displayname, newsletter, folder_id, verified_code, account_created) VALUES (:email, :password, :displayname, :newsletter, :folder_id, :verified_code, :account_created)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':displayname', $displayname);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->bindParam(':verified_code', $verified_code);
                $stmt->bindParam(':newsletter', $newsletter);
                $stmt->bindParam(':account_created', $account_created);

                $stmt->execute();


                $to = $email;
                $subject = "Verificatie Jelte's eCloud";
                
                $headers = array(
                    "MIME-Version" => "1.0",
                    "Content-Type" => "text/html; charset=UTF-8",
                    "From" => "support@jeltecost.nl",
                    "Replay-To" => "support@jeltecost.nl",
                );
                
                
                $message = file_get_contents('actions/functions/template.php');
                $message2 = str_replace('{{title_subject}}', 'Verificatie', $message);

                $message3 = str_replace('{{body_title}}', "Verificatie voor Jelte's eCloud", $message2);
                $message4 = str_replace('{{body_content}}', 'Bedankt voor het aanmelden bij Jeltes eCloud. We zijn heel blij dat u heeft geregistreerd bij onze Cloud. Druk op Confirm Account om ur account te verifiëren', $message3);
                $message5 = str_replace('{{body_content2}}', 'Als de link niet werkt graag via deze link verifiëren <a href = "https://jeltecost.nl/verify.php?email=' . $email . '&code=' . $verified_code .'"></a>', $message4);

                $message6 = str_replace('{{button_text}}', 'Verifieer hier', $message5);
                $message7 = str_replace('{{button_link}}', 'https://jeltecost.nl/verify.php?email=' . $email . '&code=' . $verified_code, $message6);

                $send = mail($to, $subject, $message7, $headers);
                $alert =  ($send ? 'Account is aangemaakt. Check je email voor de verificatielink.' : 'Er was een probleem. Gebruik een ander email adress.');
            } else {
                $alert = "Wachtwoorden komen niet overeen.";
            }
            
        }




        
    }
}

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

    <!-- CSS --><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="./mycloud/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

</head>
<!-- This style is used to make the login page look nicer -->
<style>
body {
    background-image: url("./images/background.jpg");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
}
</style>


<html>
<body>
    <div class="header" id="topnav">
    <a class="logo" href="">Jelte's eCloud</a>

        <a href="javascript:void(0);" class="icon" onclick="MenuButtonClick()">
            <i class="fa fa-bars"></i>
        </a>
        <!-- <div class="header-right">
            <p>Gemaakt door <b>Jelte</b></p>
        </div> -->

        <div class="header-left" id="myLinks">
            <a class="link" href="./index.php">Log in</a>
        </div>
    </div>


    <form id="form_login"  method="post">
        <h2>Registreren</h2>
        <?php
            if (!empty($alert)) {
                echo '<p class="alert">';
                echo $alert;
                echo '</p>';
            }
        ?>
        <p>
            <input type="email" name="email" id="email" placeholder="E-mail" autocomplete="off" required/>
        </p>
        <p>
            <label>Uw wachtwoord moet minimaal 8 letters bevatten en een cijfer.</label>
        </p>
        <p>
            <input type="password" name="password" id="password" placeholder="wachtwoord" autocomplete="off" required/>
        </p>
        <p>
            
            <input type="password" name="password2" id="password2" placeholder="herhaal wachtwoord" autocomplete="off" required/>
        </p>
        <p>
            <input type="checkbox" name="newsletter" id="newsletter" value="newsletter" checked>
            <label for="newsletter">Ja, ik wil graag een nieuwsbrief ontvangen.</label>
        </p>
        <p>
            <button id="submit" type="submit" name="submit">Registreren</button>
        </p>
        <p>
            Heeft u al een account? Druk <a href="./index.php" >hier</a> om in te loggen
        </p>
    </form>

        
</body>

<!-- Ja die ene go up knopje -->
<!-- <a id="myBtn" href="#topnav">Go up</a> -->

<script>
    var mybutton = document.getElementById("myBtn");

    window.onscroll = function() {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }


    function MenuButtonClick() {
        var x = document.getElementById("myLinks");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }


    window.addEventListener("resize", function() {
        if (window.matchMedia("(min-width: 1100px)").matches) {
            console.log("Screen width is at least 1100px")
            var x = document.getElementById("myLinks");
            if (x.style.display === "none") {
                x.style.display = "block";
            }
        }

    })
</script>



</html>