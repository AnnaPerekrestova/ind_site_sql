<?php
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
else 


mysqli_set_charset($link, 'utf8');

?>
