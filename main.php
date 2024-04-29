<?php
    if(!isset($_COOKIE['username'])) {
        header('Location: loginPage.php');
    }
    else {
        $username = $_COOKIE['username'];
        include("connect.php");

        try {
            $sqlSelect = "select count(*) as count from data join users ON users.id = data.usernameId where users.username = :username";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
            $res = $stmt->fetchAll();
            
            foreach ($res as $element) {
                $serviceNameCount = $element[0];
            }

            $sqlSelect = "select serviceName, serviceUsername, servicePassword, serviceInfo from data join users ON users.id = data.usernameId where users.username = :username";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
            $res = $stmt->fetchAll();

        }

        catch(PDOException $ex) {   // Обробка виключень
            echo $ex->GetMessage();     // Виведення повідомлення про помилку
        }

        $dbh = null;
    }
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Облікові дані</title>
    <link rel="stylesheet" href="stylesMain.css">
</head>
<body>
    <header>
        <h1 id="authdataTitle" class="headerElement">Облікові дані</h1>
        <h2 id="usernameBlock" class="headerElement">
            <?php
                echo("Вітаю, $username!");
            ?>
        </h2>
    </header>
    <div  id="deleteAccountButton">
        <a href='deleteAccountProcess.php'>Видалити акаунт</a>
    </div>
    <div id=dataAddBlock>
        <form action=dataAdd.php method=post>
            <input type=submit value=+ id=dataAddButton>
        </form>
        
    </div>
    <?php
        if ($serviceNameCount==0) {
            echo "<h3 id=dataNone> У вас немає жодних даних!</h3>";
        }
        else {
            foreach ($res as $element) {
                $serviceName = $element[0];
                $serviceUsername = $element[1];
                $servicePassword = $element[2];
                $serviceInfo = $element[3];
                
                echo "<div id=serviceName>";
                echo "<h3 id=name>$serviceName</h3> <br>";
                echo "<b>Логін:</b> $serviceUsername <br>";
                $key = "secret_encrypt_key";
                $decryptedServicePassword = openssl_decrypt($servicePassword, 'aes-256-cbc', $key, 0, $iv);
                echo "<b>Пароль:</b> $decryptedServicePassword <br>";
                if ($serviceInfo != NULL) {
                    echo "<b>Додаткова інформація:</b> $serviceInfo <br>";
                }

                echo "<br><a href=dataEdit.php?serviceName=$serviceName>Редагувати</a>";
                echo "<br><a href=deletedataProcess.php?serviceName=$serviceName>Видалити</a>";

                echo "</div>";
                
            }
        }
    ?>
    <div id="logoutBlock">
        <a href="logoutProcess.php">Вийти</a>
    </div>
    <div id="deleteAccountBlock">
        
    </div>
</body>
</html>