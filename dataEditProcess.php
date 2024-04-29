<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serviceName = $_POST['serviceName'];
        $serviceUsername = $_POST['serviceUsername'];
        $servicePassword = $_POST['servicePassword'];
        $serviceInfo = $_POST['serviceInfo'];
        $serviceId = $_GET['serviceId'];
        $username = $_COOKIE['username'];

        $key = "secret_encrypt_key";
        $cryptedServicePassword = openssl_encrypt($servicePassword, 'aes-256-cbc', $key, 0, $iv);
        
        $serviceIsPresent = false;

        include("connect.php");

        try {
            $sqlSelect = "select data.id, data.serviceName from data join users on users.id = data.usernameId where users.username = :username and data.serviceName = :serviceName";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":serviceName", $serviceName);
            $stmt->execute();
            $res = $stmt->fetchAll();
            foreach ($res as $element) {
                if ($element[1] != NULL) {
                    if($element[0] != $serviceId){
                        $serviceIsPresent = true;
                    }
                }
            };

            if ($serviceIsPresent == true) {
                setcookie('error', 1); // Сервіс з таким ім'ям вже існує!
                header('Location: dataEdit.php');
            }

            else {
                $sqlSelect = "update data set
                serviceName = :serviceName,
                serviceUsername = :serviceUsername,
                servicePassword = :servicePassword
                where id = :serviceId";
                $stmt = $dbh->prepare($sqlSelect);
                $stmt->bindValue(":serviceName", $serviceName);
                $stmt->bindValue(":serviceUsername", $serviceUsername);
                $stmt->bindValue(":servicePassword", $cryptedServicePassword);
                $stmt->bindValue(":serviceId", $serviceId);
                $stmt->execute();
    
                if ($serviceInfo != NULL) {
                    $sqlSelect = "update data set
                        serviceInfo = :serviceInfo
                        where id = :serviceId";
                    $stmt = $dbh->prepare($sqlSelect);
                    $stmt->bindValue(":serviceInfo", $serviceInfo);
                    $stmt->bindValue(":serviceId", $serviceId);
                    $stmt->execute();
                }
                else {
                    $sqlSelect = "update data set
                        serviceInfo = NULL
                        where id = :serviceId";
                    $stmt = $dbh->prepare($sqlSelect);
                    $stmt->bindValue(":serviceId", $serviceId);
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