<?php
include "ind1.php";
if (!$link) 
{
  echo( "<P>В настоящий момент сервер базы данных не доступен, поэтому 
            корректное отображение страницы невозможно.</P>" );
  exit();
}
else
{
  
  $cust = mysqli_query($link, "select c.customer_id,ex.name,c.customer_fio,c.customer_name,c.customer_room,c.chief from customer c INNER JOIN exempt_table ex ON ex.exempt_type=c.customer_type; ");
  if($cust)
  {
    echo '<form name="feedback" action="" method="post">';
    // Определяем таблицу и заголовок
    echo "<table border=1>";
    echo "<tr><td>ID</td><td>тип клиента</td><td>ФИО</td><td>Название отдела</td><td>Ном.комнаты</td><td>Руковолитель отдела</td><td>Подробная информация</td><td>Изменить данные о клиенте</td></tr>";
    // Так как запрос возвращает несколько строк, применяем цикл
    while($customers = mysqli_fetch_array($cust))
    {
      echo "<tr><td>".$customers['customer_id']."&nbsp;</td><td>".$customers['name']."&nbsp </td><td>".$customers['customer_fio']."&nbsp;</td><td>"
      .$customers['customer_name']."&nbsp;</td><td>".$customers['customer_room']."&nbsp;</td><td>".$customers['chief'].'&nbsp;</td><td><a href="ind_cust_inf.php?id='.$customers['customer_id'].'">Узнать подробнее</a></td><td><a href="ind_change_customer.php?id='.$customers['customer_id'].'">Изменить</a></td></tr>';
  //<td> <INPUT name="button_'.$customers['customer_id'].'" id="button_'.$customers['customer_id'].'" type="submit" value="Узнать подробнее"></td>
    }
    

  }
  else
  {
    echo "<p><b>Error: ".mysqli_error($link)."</b><p>";
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="ind_css3.css">
  <title>Таблица клиетнов</title>
</head>
<body>
  
</body>
</html>