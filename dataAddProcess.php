<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serviceName = $_POST['serviceName'];
        $serviceUsername = $_POST['serviceUsername'];
        $servicePassword = $_POST['servicePassword'];
        $serviceInfo = $_POST['serviceInfo'];
        
        $username = $_COOKIE['username'];

        $serviceIsPresent = false;

        include("connect.php");

        try {
            $sqlSelect = "select serviceName from data join users ON users.id = data.usernameId where users.username = ':username' and data.serviceName = ':sericeName'";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":serviceName", $serviceName);
            $stmt->execute();
            $res = $stmt->fetchAll();
    
            foreach ($res as $element) {
                if ($element[0] != NULL) {
                    $serviceIsPresent = true;
                }
            };

            if ($serviceIsPresent == true) {
                setcookie('error', 1); // Сервіс з таким ім'ям вже існує!
                header('Location: contactAdd.php');
            }

            else {
                $sqlSelect = "select id from users where username = :username";
                $stmt = $dbh->prepare($sqlSelect);
                $stmt->bindValue(":username", $username);
                $stmt->execute();
                $res = $stmt->fetchAll();

                foreach ($res as $element) {
                    $usernameId = $element[0];
                }

                $key = "secret_encrypt_key";
                $cryptedServicePassword = openssl_encrypt($servicePassword, 'aes-256-cbc', $key, 0, $iv);

                $sqlSelect = "insert into data (usernameId, serviceName, serviceUsername, servicePassword)
                values (:usernameId, :serviceName, :serviceUsername, :servicePassword)";
                $stmt = $dbh->prepare($sqlSelect);
                $stmt->bindValue(":usernameId", $usernameId);
                $stmt->bindValue(":serviceName", $serviceName);
                $stmt->bindValue(":serviceUsername", $serviceUsername);
                $stmt->bindValue(":servicePassword", $cryptedServicePassword);
                $stmt->execute();
    
                if ($serviceInfo != "") {
                    $sqlSelect = "update data
                    set serviceInfo = :serviceInfo
                    where usernameId = :usernameId and serviceName = :serviceName";
                    $stmt = $dbh->prepare($sqlSelect);
                    $stmt->bindValue(":serviceInfo", $serviceInfo);
                    $stmt->bindValue(":usernameId", $usernameId);
                    $stmt->bindValue(":serviceName", $serviceName);
                    $stmt->execute();
                }

                header('Location: main.php');
            }
        }

        catch(PDOException $ex) {   // Обробка виключень
            echo $ex->GetMessage();     // Виведення повідомлення про помилку
        }
    
        $dbh = null;
    }
?>