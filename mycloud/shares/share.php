
<?php
    //Actor: Jelte Cost
    //Description: Log in scherm voor Driezie's eCloud


// Verbinding met de database folder
require_once '../../actions/db/db_connect.php';
$dbh = getDB();

// check if logged in
session_start();
if (!isset($_SESSION['session_email'])) {
header('Location: ../');


} else {
?>
<script>
    console.log("Valid login with email");
</script>
<?php    

}

// if post logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ./');
}


?>

<!DOCTYPE html>
<html lang="en">


<head>
<title>Your eCloud</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">


<!-- CSS -->
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />


</head>
<html>
<body>
    <div class="header" id="topnav">
        <a class="logo" href="">Jelte's eCloud</a>
        <a href="javascript:void(0);" class="icon" onclick="MenuButtonClick()"><i class="fa fa-bars"></i></a>

        <div class="header-left" id="myLinks">
            <!-- <a href="../settings/profile.php">Mijn Profiel</a> -->
        </div>
        <div class="header-right" id="myLinks">
            <form style= "margin: 0;" action="" method="post">
            <a href=""><b style="margin-right: 10px"> <?= $_SESSION['session_displayname'] ?></b><!--<img  src="../img/img_avatar.png" alt="Avatar" class="avatar">--></a>
            <input style="color: black; text-align: center; padding: 20px 25px 20px 25px; text-decoration: none; font-size: 18px; cursor: pointer; border: 0; background-color: rgba(255, 255, 255, 0);" id="logout" class="logout" type="submit" name="logout" value="Logout">
        </form>
        </div>
    </div>
    <div style="display:flex; height: 100%;">
        <div class='main-container'>
            <ul class="header-style" id="myLinks">
                <li><a href="../">Mijn bestanden</a></li>
                <li><a href="../upload/">Upload bestanden</a></li>
                <li><a href="">Gedeelde bestanden</a></li>
            </ul>
        </div>
            

        <div style="overflow-x:auto; padding: 10px 10px 10px 10px">
        <!-- <div><p class="alert">Deze functie is op dit moment niet beschikbaar. Probeer het later opnieuw!</p></div> -->

            <p>
                <?php
                // get the name of the id
                $sql = "SELECT * FROM files WHERE id = :id AND file_uploader = :user_id";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->bindParam(':user_id', $_SESSION['session_id']);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    echo 'Deel <b>'. $row['file_name'] . '</b> met andere gebruikers:<br>';
                }
                


                ?>
            </p>

            <input type="text" id="search" placeholder="Zoeken via email">

            <input type="submit" value="Share">
        </div>

    </div>

</body>

<!-- Ja die ene go up knopje -->
<a id="myBtn" href="#tonav">Go up</a>

<script>
    function search() {
        var x = document.getElementById("myLinks");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }


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