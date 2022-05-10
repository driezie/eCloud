<?php
    //Actor: Jelte Cost
    //Description: Log in scherm voor Driezie's eCloud


// Verbinding met de database folder
require_once '../../actions/db/db_connect.php';
$dbh = getDB();

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

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ./');
}

function message($methode,$smg) {
    echo '<p class="'.$methode.'">'.$smg.'</p>';
}

// check if there has a post action
if (isset($_GET['action'])) {
    // check if post os recieve
    if ($_GET['action'] == 'recieve') {
        $user_current_email = $_GET['user_recieved_email'];


        // get id from user_current_email
        $sql = "SELECT id FROM users WHERE email = '$user_current_email'";
        $result = $dbh->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['id'];

        $file_id = $_GET['id'];
        // make update query for recieved
        $sql = "UPDATE shares SET received = 'Y' WHERE file_id = $file_id AND user_recieved = $user_id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        // check if query is succesfull
        if ($stmt) {
            header('Location: index.php?alert=Uw bestand is succesvol ontvangen');
        } else {
            header('Location: index.php?alert=Er ging iets mis');
        }
    }
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

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
            <a href="../../settings/profile.php">Mijn Profiel</a>
        </div>
        <div class="header-right" id="myLinks">
            <form style= "margin: 0;" action="" method="post">
            <a href="../settings/profile.php"><b style="margin-right: 10px"> <?= $_SESSION['session_displayname'] ?></b><!--<img  src="../img/img_avatar.png" alt="Avatar" class="avatar">--></a>
            <input style="color: black; text-align: center; padding: 20px 25px 20px 25px; text-decoration: none; font-size: 18px; cursor: pointer; border: 0; background-color: rgba(255, 255, 255, 0);" id="logout" class="logout" type="submit" name="logout" value="Logout">
        </form>
        </div>
    </div>
    <div style="display:flex; height: 100%;">
        <div class='main-container'>
            <ul class="header-style" id="myLinks">
                <li><a href="../../mycloud/">Mijn bestanden</a></li>
                <li><a href="../upload/">Upload bestanden</a></li>
                <li><a href="">Gedeelde bestanden</a></li>
            </ul>
        </div>
        
            

        <div style="overflow-x:auto;">
        <div>
        
            <?php

                if (isset($_GET['alert'])) {
                    message('alert',$_GET['alert']);  
                } elseif (isset($_GET['notify'])) {
                    message('notify',$_GET['notify']);                  
                }
            ?>
        </div>

        <div style="display: flex;">
            <table id="myTable">
                <tr>      
                    <th style="max-width: auto">Naam</th>
                    <th style="max-width: auto">Locatie</th>
                    <th style="max-width: auto">Grootte</th>
                    <th style="max-width: auto"></th> 
                    <th style="max-width: auto"></th>   
                </tr>

                <?php
                $user_id = $_SESSION['session_id'];
                $sql = "SELECT * FROM shares WHERE user_recieved = '$user_id'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();
                // print retult with key and value
                foreach ($result as $key => $value) {
                    $file_id = $value['file_id'];
                }
                    echo '<tr>';
                    // check if recieved is Y
                    if ($value['received'] == 'Y') {
                        //echo '<td>'.$value['file_id'].'</td>';
                        // get file information from file_id
                        $sql = "SELECT * FROM files WHERE id = '$value[file_id]'";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        foreach ($result as $key => $value) {
                            echo '<td>'.$value['file_name'].'</td>';

                            $file_location1 = str_replace('/userid_'.$user_id, "", $value['file_destination']);
                            $file_location = str_replace("../", "", $file_location1);
                            echo '<td>'.$file_location.'</td>';
                            echo '<td>';
                            $size = $value['file_size'];
                            $size = $size / 1024;
                            $size = $size / 1024;
                            $size = round($size, 2);
                            if ($size > 1024) {
                                $size = $size / 1024;
                                $size = round($size, 2);
                                echo $size . " GB";
                            } else  {
                                echo $size . " MB";

                            }   

                            echo '</td>';
                            $file_name = $value['file_name'];
                            $file_id = $value['id'];
                            echo '<td><a href="../../actions/functions/function.php?action=download&file_name='.$file_name.'">Download</a></td>             ';
                            echo '<td><a href="../../actions/functions/function.php?action=removesharedfile&file_id='.$file_id.'">Verwijder</a></td>             ';
                        }

                    }
                    echo 'The end';
                }
                ?>

            </table>
            </div>
        </div>
    </div>


    </div>

</body>
<a id="myBtn" href="#topnav">Go up</a>

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