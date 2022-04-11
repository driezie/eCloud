<?php 

require_once '../../actions/db/db_connect.php';



if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'recieve') {
        echo $_GET['user_recieved_email'] . " is toegevoegd aan uw contacten. <br>".$_GET['file_id'];
        $file_id = $_GET['file_id'];
        $sql = "UPDATE shares SET received = 'Y' WHERE file_id = '$file_id' ";
        $stmt = getDB()->prepare($sql);
        $stmt->execute();
    }
} ?>

<a href="../../actions/"></a>