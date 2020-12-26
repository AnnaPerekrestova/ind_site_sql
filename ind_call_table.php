<?php
session_start();
if (!isset($_SESSION["user_id"]))
{
header("Location: ind_form.php");
exit();
}
include "ind1.php";
$call = mysqli_query($link, "select c.call_id,c.customer_phone, c.date_ring,r.name,c.number,c.country,c.town,c.value_min,c.summa_min,c.summa from calls c INNER JOIN rates r ON r.ring_type=c.ring_type;");
if($call)
{
  echo '<form name="feedback" action="" method="post">';
  // Определяем таблицу и заголовок
  echo "<table border=1>";
  echo "<tr><td>ID</td><td>номер телефона клиента</td><td>дата звонка</td><td>Тип звонка</td><td>вызываемый номер</td><td>Страна</td><td>Город</td><td>Время звонка(мин.)</td><td>Стоимость минуты звонка</td><td>Общая стоимость звонка</td></tr>";
  // Так как запрос возвращает несколько строк, применяем цикл
  while($c = mysqli_fetch_array($call))
  {
    echo "<tr><td>".$c['call_id']."&nbsp;</td><td>".$c['customer_phone']."&nbsp </td><td>".$c['date_ring']."&nbsp;</td><td>"
    .$c['name']."&nbsp;</td><td>".$c['number']."&nbsp;</td><td>".$c['country']."&nbsp;</td><td>".$c['town']."&nbsp;</td><td>".$c['value_min']."&nbsp;</td><td>".$c['summa_min']."&nbsp;</td><td>".$c['summa']."&nbsp;</td> </tr>";
  }
  echo "</table>";
}
else
{
  echo "<p><b>Error: ".mysqli_error($link)."</b><p>";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="ind_css3.css">
  <meta charset="UTF-8">
  <title>Таблица звонков</title>
</head>
<body>
  
</body>
</html>