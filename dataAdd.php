<?php
    if(!isset($_COOKIE['username'])) {
        header('Location: loginPage.php');
    }
    else {
        $username = $_COOKIE['username'];

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
    <title>Додати дані</title>
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
        <form action=dataAddProcess.php method=post>

            <div id="dataBlock">
                <label for="dataElement">Назва</label> <br>
                <input type="text" name="serviceName" id="dataElement" required autofocus> <br>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Логін/E-mail</label> <br>
                <input type="text" name="serviceUsername" id="dataElement" required> <br>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Пароль</label> <br>
                <input type="text" name="servicePassword" id="dataElement" required> <br>
            </div>

            <div id="dataBlock">
                <label for="dataElement">Додаткова інформація</label> <br>
                <input type="text" name="serviceInfo" id="dataElement"> <br>
            </div>

            <input type=submit value=Додати id=dataAddButton>
        </form>
    </div>
    <div id="cancelBlock">
        <a href="main.php">Скасувати</a>
    </div>
    </body>
</html>