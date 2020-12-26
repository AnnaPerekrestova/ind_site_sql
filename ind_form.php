<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="ind_css2.css">
	<meta charset="UTF-8">
	<title>login</title>
</head>
<body>
<div class="container">
<form action="" method="POST">
Логин <input name="login" type="text" required><br>
Пароль <input name="password" type="password" required><br>
<input class="dws-submit" name="button" type="submit" value="Войти">


</form>
<a href="ind_reg.php">Добавить нового пользователя	</a>	
</div>
</body>
</html>

<?php
session_start();
include ("ind1.php");
if (isset($_SESSION['user_id'])){
header('Location: ind_frame.php');
exit();
}

if (isset($_POST["button"])) {
	$query = "SELECT `user_salt` FROM `users` WHERE `user_login`='{$_POST['login']}'";
	$sql=mysqli_query($link,$query) or die (mysqli_error($link));
	if (mysqli_num_rows($sql)==1)
	{
		$row = mysqli_fetch_assoc($sql);
		$salt=$row['user_salt'];
		$password = md5(md5(trim($_POST['password'])).$salt);

		$query = "SELECT `user_id` FROM `users` WHERE `user_login`='{$_POST['login']}' AND `user_password`='{$password}'";
		$sql=mysqli_query($link, $query) or die (mysqli_error($link));
		if (mysqli_num_rows($sql)==1)
		{	
			$row = mysqli_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['user_id'];
			header("Location: ind_frame.php");
			exit();
		}
		else
		{
			die ('такой логин с паролем не найдены в базе данных. ');
		}
	}
	else
	{
		die ('такой логин не найден в базе данных.');
	}
}
?>