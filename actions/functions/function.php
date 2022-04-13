<?php
// Verbinding met de database folder
require_once '../db/db_connect.php';
$dbh = getDB();

// check if logged in
session_start();
if (!isset($_SESSION['session_email'])) {
    header('Location: ../index.php');
}

// check if can_delete is set to Y from database
$stmt = $dbh->prepare("SELECT can_delete FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['session_id']);
$stmt->execute();
$user = $stmt->fetch();
// if not set to Y, redirect to index.php


// check if post action then check if post is logout
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete') {
        $user_id = $_SESSION['session_id'];
        if ($user['can_delete'] == 'Y') {
            $file_name = $_GET['file_name'];

            // get if from file where file uploader is the same as the user and filename is $file_name
            $sql = "SELECT * FROM files WHERE file_uploader = :user_id AND file_name = :file_name";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':file_name', $file_name);
            $stmt->execute();
            $file = $stmt->fetch();
            // if file is found, delete the file
            $sql = "DELETE FROM files WHERE file_uploader = :user_id AND file_name = :file_name";


            echo $file_name;
            echo "<br>";
            echo $user_id;
            echo "<br>";
            echo "<br>";
            echo $sql;
            echo "<br>";
            echo "<br>";
            if ($file) {
                echo "File found<br>";
                


                $file = "../../myuploads/userid_".$user_id.'/' . $file_name;
                unlink($file);
                // check if unlinked
                if (file_exists($file)) {
                    echo "File not deleted";
                    header('Location: ../../index.php?notify='.$file_name. 'is NIET verwijderd! Probeer opnieuw.');
                } else {
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':file_name', $file_name);
                    $stmt->execute();
                    echo 'Succes!!';
                    header('Location: ../../index.php?notify='.$file_name. 'is verwijderd!');
                }
            }   else {
                echo "File not found";
            }
        }else {
            header('Location: ../../index.php?notify=U heeftt geen rechten om bestanden te verwijderen');
            exit();
        }
    } 
    if ($_GET['action'] == 'download') {
        $user_id = $_SESSION['session_id'];
        $file_name = $_GET['file_name'];

        $filepath = "myuploads/userid_".$user_id. '/' . $file_name;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
    }
    if ($_GET['action'] == 'updateprofile') {
        $user_id = $_SESSION['session_id'];
        

        $displayname = $_POST['displayname'];

        $user_newsletter = $_POST['newsletter'];
        $user_share = $_POST['share'];

        if (empty($displayname)) {
            header('Location: ../../settings/profile.php?alert=Vul een display naam in.');
        } else {

            if (isset($user_newsletter)) {
                $user_newsletter = 'Y';
            } else {
                $user_newsletter = 'N';
            }

            if (($user_share) == 'share') {
                $user_share = 'Y';
            } else {
                $user_share = 'N';
            }

            
            $_SESSION['session_displayname'] = $displayname;

            $sql = "UPDATE `users` SET `displayname` = '$displayname', `newsletter` = '$user_newsletter', `share` = '$user_share' WHERE `users`.`id` = '$user_id'";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                header('Location: ../../settings/profile.php?notify=Profiel is aangepast!');
            } else {
                header('Location: ../../settings/profile.php?alert=Er zijn geen wijzigingen op uw profiel geweest.');
            }
        }
    }
    if ($_GET['action'] == 'deleteallfiles') {
        echo "delete all files";
        $user_id = $_SESSION['session_id'];
        $dirname = "../../myuploads/userid_".$user_id;
        array_map('unlink', glob("$dirname/*.*"));
        rmdir($dirname);
        if (file_exists($dirname)) {
            header('Location: ../../settings/profile.php?alert=Files not deleted, please try again or contact support (support.jeltecost.nl)');
        } else {
            // delete files from database with user id
            $sql = "DELETE FROM `files` WHERE `files`.`file_uploader` = '".$user_id."'";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                header('Location: ../../settings/profile.php?notify=Succes! Alle bestanden zijn verwijderd.');
                // $receiver = "j.cost@cadicto.nl";
                // $subject = "Account verwijderd Jelte's eCloud";
                // $body = "Er is een user verwijderd met de email: ".$_SESSION['session_email']."\n\nHieronder zijn de bestanden die verwijderd zijn: \n\n ".print_r( $files, true );;
                // $sender = "From: support@jeltecost.nl";
                // mail($receiver, $subject, $body, $sender);
            } else {
                header('Location: ../../settings/profile.php?alert=We kunnen geen bestanden vinden in uw Cloud. <a href="../mycloud/upload">Voeg meer toe</a>');
            }
        }
        
    }
    if ($_GET['action'] == 'sharefile') {

        $file_id = $_POST['file_id'];
        $user_sender_id = $_SESSION['session_id'];
        $user_sender_email = $_SESSION['session_email'];
        $user_sender_displayname = $_SESSION['session_displayname'];
        $user_reciever_email = $_POST['to_user'];

        // Een query om de gebruiker te vinden die verbonden is met de email
        $sql = "SELECT * FROM `users` WHERE `id` = '$user_sender_id'";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $current_user = $stmt->fetchAll();
        // is can_share gevuld?
        


        // Een query om de gebruiker te vinden die verbonden is met de email
        $sql = "SELECT * FROM `users` WHERE `email` = '$user_reciever_email'";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $user_reciever = $stmt->fetchAll();

        //echo 'Verstuurder: '. $user_sender_id.' - '.$user_sender_email. '<br>';
            //echo "Can_Share: ".$current_user[0]['can_share']."<br><br>";
        
        if ($stmt->rowCount() > 0) {
            $user_reciever_id = $user_reciever[0]['id'];
            $user_reciever_enabledshare = $user_reciever[0]['share'];
            //echo 'Ontvanger: '.$user_reciever_id .' - '.$user_reciever_email . '<br>Share enabled: '.$user_reciever_enabledshare.'<br>';
        }
        
        //echo '<br><br><br>';
        // Hier checkt t of de gebruiker kan delen (dit kan bewerkt worden door een administrator)
        if ($current_user[0]['can_share'] == 'Y') {
            //echo 'Gebruiker kan delen met mensen<br>';
            // Hier checkt t of de gebruiker naar waar het wordt gestuurd het delen heeft ingeschakeld
            if ($user_reciever_enabledshare == 'Y') {
                //echo 'Gebruiker heeft delen aanstaan!<br>';

                // Hier checkt t of de gebruiker zelf het bestand niet al heeft gedeeld met hetzelfde persoon
                $sql = "SELECT * FROM `shares` WHERE `file_id` = '$file_id' AND `user_recieved` = '$user_reciever_id'";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                $shared_file = $stmt->fetchAll();
                if ($stmt->rowCount() > 0) {
                    header('Location: ../../mycloud/shares/share.php?alert=Gebruiker heeft dit bestand al gedeeld met deze persoon&id='.$file_id);
                } else {
                    //echo 'Gebruiker heeft dit bestand nog niet gedeeld met deze persoon<br>';

                    // Hij slaat nu alle gegevens op in de database. Dit is de file_id, de gebruiker die het bestand heeft gedeeld, de gebruiker die het bestand heeft ontvangen, en de datum waarop het bestand is gedeeld.
                    $stmt = $dbh->prepare("INSERT INTO shares (file_id, user_send, user_recieved) VALUES (:file_id, :user_send, :user_recieved)");
                    $stmt->bindParam(':file_id', $file_id);
                    $stmt->bindParam(':user_send', $user_sender_id);
                    $stmt->bindParam(':user_recieved', $user_reciever_id);
                    $stmt->execute();

                    // check of de query is gelukt

                    // Hier pakt t de naam van het bstand (dit wordt alleen gebruikt in de email)
                    $sql = "SELECT file_name FROM `files` WHERE `id` = '$file_id'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    $file = $stmt->fetchAll();
                    $file_name = $file[0]['file_name'];


                    if ($stmt->rowCount() > 0) {
                        //echo 'In database uploaded<br>';

                        // Nu wordt de email verstuurd met de link om het bestand te de accepteren zodat het in zijn database komt te staan.
                        $to = $user_reciever_email;

                        // title van de email
                        $subject = "Bestand gedeeld Jelte's eCloud";
                        
                        $headers = array(
                            "MIME-Version" => "1.0",
                            "Content-Type" => "text/html; charset=UTF-8",
                            "From" => "support@jeltecost.nl",
                            "Replay-To" => "support@jeltecost.nl",
                        );
                        
                        
                        $message = file_get_contents('template.php');

                        // Inhoud van de email
                        $message2 = str_replace('{{title_subject}}', 'Bestand Gedeeld', $message);

                        $message3 = str_replace('{{body_title}}', "Er is een bestand gedeeld voor jou", $message2);
                        $message4 = str_replace('{{body_content}}', $user_sender_email.'<b>('.$user_sender_displayname.')</b> heeft een bestand gedeeld met jou: ' . $file_name, $message3);
                        $message5 = str_replace('{{body_content2}}', '', $message4);

                        $message6 = str_replace('{{button_text}}', 'Open gedeelde bestand', $message5);
                        $message7 = str_replace('{{button_link}}', 'https://jeltecost.nl/mycloud/shares/index.php?action=recieve&user_recieved_email=' . $user_reciever_email . '&id=' . $file_id .'', $message6);

                        // email wordt verstuurd
                        $send = mail($to, $subject, $message7, $headers);
                        // hier checkt t of dit succesvol is gebeurt
                        header('Location: ../../mycloud/shares/share.php?notify=Er is een mail gestuurd naar '.$to.'.&id='.$file_id);

                    } else {
                        header('Location: ../../mycloud/shares/share.php?alert=Niet in database geupload&id='.$file_id);

                    }
                }

                
            } else {
                header('Location: ../../mycloud/shares/share.php?alert=Kan gebruiker niet vinden. Misschien staat het delen uit?&id='.$file_id);
            }
        } else {
            header('Location: ../../mycloud/shares/share.php?alert=Gebruiker kan niet delen met mensen&id='.$file_id);
        }
    }
    if ($_GET['action'] == 'removesharedfile') {
        $user_id = $_SESSION['session_id'];
        $file_id = $_GET['file_id'];
        $sql = "DELETE FROM shares WHERE file_id = :file_id AND user_recieved = :user_recieved";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':file_id', $file_id);
        $stmt->bindParam(':user_recieved', $user_id);
        $stmt->execute();
        // check of de query is gelukt
        if ($stmt->rowCount() > 0) {
            header('Location: ../../mycloud/shares/index.php?alert=Bestand is verwijderd van uw gedeelde bestanden.&id='.$file_id);
        } else {
            echo 'Not deleted';
        }
    }
    if ($_GET['action'] == 'removesharedfileviasender') {
        $user_send = $_SESSION['session_id'];
        $file_id = $_GET['file_id'];
        $sql = "DELETE FROM shares WHERE file_id = :file_id AND user_send = :user_send";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':file_id', $file_id);
        $stmt->bindParam(':user_send', $user_send);
        $stmt->execute();
        // check of de query is gelukt
        if ($stmt->rowCount() > 0) {
            header('Location: ../../mycloud/shares/share.php?alert=Bestand is verwijderd van uw gedeelde bestanden.&id='.$file_id);
        } else {
            echo 'Not deleted';
        }
    }
}

?>
<br>
<br>
<a href="../../">Back to home</a>


