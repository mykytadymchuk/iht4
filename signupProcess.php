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
                    }
                }

                if ($userIsPresent == true) { // Користувач існує
                    setcookie('error', 1); // Користувач з таким логіном вже існує!
                    header('Location: signupPage.php');
                }
    
                else {  // Користувача не існує
                    if (isset($_POST['password'])) {
                        $password = $_POST['password'];
                        $options = [
                            'cost' => 12,
                        ];
                        $hasedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

                        $sqlSelect = "insert into users (username, password)
                            values (:username, :password)";
                        $stmt = $dbh->prepare($sqlSelect);
                        $stmt->bindValue(":username", $username);
                        $stmt->bindValue(":password", $hasedPassword);
                        $stmt->execute();

                        setcookie('username', $username, time() + 3600);
                        //setcookie('sort', 'contactName', time() + 3600);
                        header('Location: main.php');
                    }
                }
            }
        
            catch(PDOException $ex) {   // Обробка виключень
                echo $ex->GetMessage();     // Виведення повідомлення про помилку
            }
        
            $dbh = null;

        }
    }
?>