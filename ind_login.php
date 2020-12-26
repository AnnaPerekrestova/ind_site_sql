</*?php

session_start();
if (isset($_SESSION["user_id"]))
{
header("Location: ind_frame.php");
exit();
}


if (!empty($_POST)){
$login = (isset($_POST['login'])) ? mysqli_real_escape_string($_POST['login']) : '';	
}
$query = "SELECT `user_salt` FROM `users` WHERE `user_login`='{$login}'"
$sql=mysqli_query($query) or die (mysqli_error());
if (mysqli_num_rows($sql)==1)
{
	$row = mysqli_fetch_assoc($sql);
	$salt=$row['user_salt'];
	$password = md5(md5($_POST['password']).$salt);

	$query = "SELECT `user_id` FROM `users` WHERE `user_login`='{$login}' AND `user_password`='{$password}'";
	$sql=mysqli_query($query) or die ((mysqli_error());
	if (mysqli_num_rows($sql)==1)
	{
		$row = mysqli_fetch_assoc($sql);
		$_SESSION['user_id'] = $row['user_id'];
		header("Location: ind_frame.php");
		exit();

	}
	else
	{
		die ('такой логин с паролем не найдены в базе данных. <a = href="ind_reg.php">Авторизоваться</a>');
	}
}
else
	{
		die ('такой логин не найден в базе данных. <a = href="ind_reg.php">Авторизоваться</a>');
	}
	*/
?>
