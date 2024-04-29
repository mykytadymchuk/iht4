<?php
    include("connect.php");
    //error_reporting(0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['username'])) {
            $username = $_POST['username'];

            try {
                $sqlSelect = "select username, password from users where username = :username";
                $stmt = $dbh->prepare($sqlSelect);
                $stmt->bindValue(":username", $username);
                $stmt->execute();
                $res = $stmt->fetchAll();

                $userIsPresent = false;
                foreach($res as $element) {
                    if ($element[0] != NULL) {
                        $userIsPresent = true;
                        $dbPassword = $element[1];
                    }
                }

                if ($userIsPresent == true) { // Користувач існує
                    if (isset($_POST['password'])) {
                        $password = $_POST['password'];
                        if (password_verify($password, $dbPassword)) {
                            setcookie('username', $username, time() + 3600);
                            //setcookie('sort', 'contactName', time() + 3600);
                            header('Location: main.php');
                        }
                        else {
                            setcookie('error', 1); // Неправильний логін або пароль!
                            header('Location: loginPage.php');
                        }
                    }
                }

                else {  // Користувача не існує
                    setcookie('error', 1); // Неправильний логін або пароль!
                    header('Location: loginPage.php');
                }
            }
        
            catch(PDOException $ex) {   // Обробка виключень
                echo $ex->GetMessage();     // Виведення повідомлення про помилку
            }
        
            $dbh = null;

        }
    }
?>