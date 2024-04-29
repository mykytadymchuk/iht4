<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $username = $_COOKIE['username'];
        $serviceName = $_GET['serviceName'];
        include("connect.php");

        $sqlSelect = "select id from users where username = :username";
        $stmt = $dbh->prepare($sqlSelect);
        $stmt->bindValue(":username", $username);
        $stmt->execute();
        $res = $stmt->fetchAll();

        foreach ($res as $element) {
            $userid = $element[0];
        }

        $sqlSelect = "delete from data where usernameId = :userid and serviceName = :serviceName";
        $stmt = $dbh->prepare($sqlSelect);
        $stmt->bindValue(":userid", $userid);
        $stmt->bindValue(":serviceName", $serviceName);
        $stmt->execute();

        echo($username);
        echo "<br>";
        echo($serviceName);
        header('Location: main.php');
    }
?>