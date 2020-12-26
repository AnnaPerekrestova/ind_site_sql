<?php
// Страница регистрации нового пользователя
function GenerateSalt($n=3)
{
    $key='';
    $pattern = '1234567890abcdifghijklmnopqrstuvwxyz.,*_-=+';
    $counter = strlen($pattern)-1;
    for ($i=0; $i<$n; $i++)
    {
        $key .=$pattern{rand(0,$counter)};
    }
    return $key;
}
// Соединямся с БД
include ("ind1.php");

if(isset($_POST['submit']))
{
    $err = [];

    // проверям логин
    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='{$_POST['login']}'");
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $salt=GenerateSalt();

        $login = $_POST['login'];

        // Убераем лишние пробелы и делаем двойное хеширование
        $password = md5(md5(trim($_POST['password'])).$salt);

        mysqli_query($link,"INSERT INTO users SET user_login='".$login."', user_password='".$password."', user_salt='".$salt."'");
        header("Location: ind_form.php"); exit();
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="ind_css2.css">
    <meta charset="UTF-8">
    <title>regestration</title>
</head>
<body>
    <form method="POST" class="container">
    Логин <input name="login" type="text" required><br>
    Пароль <input name="password" type="password" required><br>
    <input name="submit" type="submit" value="Зарегистрироваться" class="dws-submit ">
</form>
</body>
</html>
