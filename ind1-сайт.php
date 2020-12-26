
<?php
// Страница авторизации

// Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

// Соединямся с БД
header('Content-Type: text/html; charset=UTF-8');
$dblocation = "localhost";
$dbname = "individ_ex1";
$dbuser = "root";
$dbpasswd = "";
$link = @mysqli_connect($dblocation,$dbuser,$dbpasswd, $dbname);
if (!$link) 
{
  echo( "<P>В настоящий момент сервер базы данных не доступен, поэтому 
            корректное отображение страницы невозможно.</P>" );
  exit();
}
else {
mysqli_set_charset($link, 'utf8');

if(isset($_POST['button']))
{
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".$_POST['login']."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
    if($data['user_password'] === md5(md5($_POST['password'])))
    {
        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        
        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");

        // Ставим куки
        setcookie("id", $data['user_id'], time()+60*60*24*30);
        setcookie("hash", $hash, time()+60*60*24*30,null,null,null,true); // httponly !!!

        // Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: ind_check.php"); exit();
    }
    else
    {
        print "Вы ввели неправильный логин/пароль";
    }
}
if(isset($_POST['new']))
{
header("Location: ind_reg.php");
}
}
?>
<form method="POST">
Логин <input name="login" type="text" required><br>
Пароль <input name="password" type="password" required><br>

<input name="button" type="submit" value="Войти">
<input name="new" type="submit" value="Добавить нового пользователя">
</form>