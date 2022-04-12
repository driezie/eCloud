<?php

// Op deze pagina checkt de gebruiker of de gegevens kloppen. 
// Als de gegevens kloppen, wordt de gebruiker doorgestuurd naar de volgende pagina.
// Dit word gestuurd via je email.


require_once './actions/db/db_connect.php';
$dbh = getDB();

$email = $_GET['email'];
$code = $_GET['code'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $row) {
    $verified_code = $row['verified_code'];
    if ($verified_code == $code) {
        if ($row['verified'] == 'Y') {
            $alert = "Account is al geactiveerd.";

            header("Refresh: 5; url=index.php");
        } else {
            $sql = "UPDATE users SET verified = 'Y' WHERE email = '$email'";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $alert = "Account is geactiveerd.";


            $to = $email;
            $subject = "Bevestiging verificatie Jelte's eCloud";
            
            $headers = array(
                "MIME-Version" => "1.0",
                "Content-Type" => "text/html; charset=UTF-8",
                "From" => "support@jeltecost.nl",
                "Replay-To" => "support@jeltecost.nl",
            );


            $receiver = $email;

            $message = file_get_contents('actions/functions/template.php');
            $message2 = str_replace('{{title_subject}}', 'Bevestiging verificatie', $message);

            $message3 = str_replace('{{body_title}}', "Bevestiging verificatie voor Jelte's eCloud", $message2);
            $message4 = str_replace('{{body_content}}', 'Bedankt voor het registreren. Uw account is succesvol geactiveerd', $message3);
            $message5 = str_replace('{{body_content2}}', '', $message4);

            $message6 = str_replace('{{button_text}}', 'Mijn Dashboard', $message5);
            $message7 = str_replace('{{button_link}}', 'https://jeltecost.nl/index.php', $message6);

            $send = mail($to, $subject, $message7, $headers);
            $alert =  ($send ? 'Account is aangemaakt. Check je email voor de verificatielink.' : 'Er was een probleem. Gebruik een ander email adress.');




            header("Refresh: 5; url=index.php");
        }
        
    } else {
        $alert = "Verificatiecode komt niet overeen.";
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
    

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="./mycloud/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

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
        </div>
    </div>


    <form id="form_login" method="post">
        <h2>
            <?php
                if (!empty($alert)) {
                    echo $alert;
                    
                    // wait 5 seconds and then go to the index page
                    header("refresh:5; url=./index.php");
                }

            ?>
        </h2>
        <!-- <?= '<p>Over 5 seconden wordt u geleid naar de login pagina.</p>'; ?> -->
    </form>

        
</body>

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