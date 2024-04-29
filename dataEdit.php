<?php
    if(!isset($_COOKIE['username'])) {
        header('Location: loginPage.php');
    }
    else {
        $username = $_COOKIE['username'];
        $serviceName = $_GET['serviceName'];

        include("connect.php");

        try {
            $sqlSelect = "select data.id, data.serviceName, data.serviceUsername, data.servicePassword, data.serviceInfo from data join users on users.id = data.usernameId where users.username = :username and data.serviceName = :serviceName";
            $stmt = $dbh->prepare($sqlSelect);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":serviceName", $serviceName);
            $stmt->execute();
            $res = $stmt->fetchAll();
            foreach ($res as $element) {
                $serviceId = $element[0];
                $serviceName = $element[1];
                $serviceUsername = $element[2];
                $servicePassword = $element[3];
                $serviceInfo = $element[4];
            }
            $key = "secret_encrypt_key";
            $decryptedServicePassword = openssl_decrypt($servicePassword, 'aes-256-cbc', $key, 0, $iv);
        }

        catch(PDOException $ex) {   // Обробка виключень
            echo $ex->GetMessage();     // Виведення повідомлення про помилку
        }

        $dbh = null;

        if(isset($_COOKIE['error'])) {
            if($_COOKIE['error']=='1') {
                echo '<script type="text/javascript">';
                echo 'alert("Дані з таким імям вже існують!");';
                echo '</script>';
            }
    
            setcookie('error', '', time() - 3600);
        }
    }
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати дані</title>
    <link rel="stylesheet" href="stylesAddData.css">
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
    <div id=dataAddBlock>
        <?php 
            echo "<form action='dataEditProcess.php?serviceId=$serviceId' method=post>";
        ?>
            <div id="dataBlock">
                <label for="dataElement">Назва</label> <br>
                <?php 
                    echo"<input type='text' name='serviceName' id='dataElement' value='$serviceName' required autofocus> <br>";
                ?>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Логін/E-mail</label> <br>
                <?php 
                    echo"<input type='text' name='serviceUsername' id='dataElement' value='$serviceUsername' required> <br>";
                ?>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Пароль</label><br>
                <?php 
                    echo "<input type='text' name='servicePassword' id='dataElement' value='$decryptedServicePassword'> <br>";
                ?>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Додаткова інформація</label><br>
                <?php 
                    echo "<input type='text' name='serviceInfo' id='dataElement' value='$serviceInfo'> <br>";
                ?>
            </div>

            <input type=submit value=Змінити id=dataAddButton>
        </form>
    </div>
    <div id="cancelBlock">
        <a href="main.php">Скасувати</a>
    </div>
    </body>
</html>