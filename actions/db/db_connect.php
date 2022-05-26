<?php
function getDB() {
    $host = 'localhost';
    $dbname = 'ecloud';
    $user = 'root';
    $pass = '';

    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $dbh;
}

