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

// print posts and gets

echo "<pre>";
print_r($_POST);
echo "</pre>";



echo "<pre>";
print_r($_GET);
echo "</pre>";

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
}

?>
<br>
<br>
<a href="../../">Back to home</a>


