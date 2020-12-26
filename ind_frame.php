<!-- Файл фреймовой структуры frame.htm -->
<?php
session_start();
if (!isset($_SESSION["user_id"]))
{
header("Location: ind_form.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="ind_css3.css">
	<meta charset="UTF-8">
	<title>Menu</title>
</head>
<frameset rows="80,*" cols="*" FRAMEBORDER="yes" BORDER="5">
	<frame src="ind_head.php" name="head" scrolling="no" >
   <frameset cols ="20%,*" FRAMEBORDER="yes" BORDER="5">
     <frame src="ind_menu.php" name="menu_wind" scrolling="no" >
     <frame src="ind_inf.php" name="main_wind">
   </frameset>
 </frameset>
<body>
	
</body>
</html>

