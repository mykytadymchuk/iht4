<?php
    $username = $_COOKIE['username'];
    include("connect.php");

    try {
        $sqlSelect = "select username from users where username = :username";
        $stmt = $dbh->prepare($sqlSelect);
        $stmt->bindValue(":username", $username);
        $stmt->execute();
        $res = $stmt->fetchAll();

        $userIsPresent = false;
        foreach ($res as $element) {
            if ($element[0] != NULL) {
                $userIsPresent = true;
            }
        };

        if ($userIsPresent == true) { // Користувач існує

            $sqlSelect = "select id from users where username = :username";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
            $res = $stmt->fetchAll();

            foreach ($res as $element) {
                $userid = $element[0];
            }

            $sqlSelect = "delete from data where usernameId = :userid";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":userid", $userid);
            $stmt->execute();

            $sqlSelect = "delete from users where id = :userid";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":userid", $userid);
            $stmt->execute();
    
            setcookie('username', '', time() - 3600);
            //setcookie('sort', '', time() - 3600);
            header('Location: loginPage.php');
        }
    
        else {  // Користувача не існує
            setcookie('error', 2);
            header('Location: loginPage.php');
        }
    }

    catch(PDOException $ex) {   // Обробка виключень
        echo $ex->GetMessage();     // Виведення повідомлення про помилку
    }

    $dbh = null;

?>