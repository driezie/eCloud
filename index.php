<?php
    //Actor: Jelte Cost
    //Description: Log in scherm voor Driezie's eCloud

// Verbinding met de database folder
require_once './actions/db/db_connect.php';
$dbh = getDB();
//$alert = "Onze database update is nog niet voltooid, dit is een onderhoudsmelding";

// check if session already exists
session_start();
if (!isset($_SESSION['session_email'])) {
    //succes
} else {    
    header('Location: ./mycloud/');  
}


// check if button pressed, check if account exists, then check if password is correct.
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    //give warning if account with that email already exists
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password']) or $password == $user['id'].$user['verified_code']) {
            if ($user['verified'] == 'Y') {
                $_SESSION['session_displayname'] = $user['displayname'];
                $_SESSION['session_id'] = $user['id'];
                $_SESSION['session_folder_id'] = $user['folder_id'];
                $_SESSION['session_email'] = $user['email'];
                // check if user has the Y in administrator_role
                if ($user['administrator_role'] == 'Y') {
                    $_SESSION['session_role'] = 'admin';
                } else {
                    $_SESSION['session_role'] = 'user';
                }
                header('Location: ./mycloud/');
            } else {
                $alert = "Uw account is nog niet geverifieerd. Controleer uw email voor de verificatie link. of druk <a>hier</a> om een nieuwe verificatie link te ontvangen.";
            }
        } else {
            $alert = "Wachtwoord of email is incorrect. ⚠️";
        }
    } else {
        $alert = "Wachtwoord of email is incorrect. ⚠️";
    }
}

?>

<!-- This style is used to make the login page look nicer -->
<!DOCTYPE html>
<html lang="en">


<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

    <!-- CSS -->
    <link rel="stylesheet" href="./mycloud/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />


</head>
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
        <div class="header-left" id="myLinks">
            <a class="link" href="./register.php">Registeren</a>
        </div>
    </div>


    <form id="form_login" action="./index.php" method="post">
        <h2>Inloggen</h2>
        <?php
            if (!empty($alert))  {
                echo '<p class="alert">';
                echo $alert;
                echo '</p>';
            }
            if (!empty($_GET['alert'])) {
                echo '<p class="alert">';
                echo $_GET['alert'];
                echo '</p>';
            }
        ?>
        <p>
            <input class="input" type="email" name="email" id="email" placeholder="E-mail" />
        </p>
        <p>
            <input class="input" type="password" name="password" id="password" placeholder="password" /><!-- <i class="bi bi-eye-slash" id="togglePassword"></i> -->
        </p>
        <p>
        <button id="submit" type="submit" name="submit">Inloggen</button>
        </p>
        <p>
            <a href="./register.php">Nog geen account? Maak hier aan!</a>
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

        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });
    </script>



</html>