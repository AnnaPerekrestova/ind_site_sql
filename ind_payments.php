<?php
include "ind1.php";
$pay = mysqli_query($link, "select p.customer_phone, p.map_nom,p.date_map,p.map_count,p.account, b.bank from payment p INNER JOIN bank b ON b.bank_id=p.bank_id;");
if($pay)
{
  echo '<form name="feedback" action="" method="post">';
  // Определяем таблицу и заголовок
  echo "<table border=1>";
  echo "<tr><td>Номер телефона клиента</td><td>Номер квитанции</td><td>Дата оплаты</td><td>Оплаченная сумма</td><td>Номер счета в банке</td><td>Банк</tr>";
  // Так как запрос возвращает несколько строк, применяем цикл
  while($c = mysqli_fetch_array($pay))
  {

    echo "<tr><td>".$c['customer_phone']."&nbsp;</td><td>".$c['map_nom']."&nbsp </td><td>".$c['date_map']."&nbsp;</td><td>".$c['map_count']."&nbsp;</td><td>".$c['account']."&nbsp;</td><td>".$c['bank']."&nbsp;</td></tr>";
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
  <title>Таблица платежей</title>
</head>
<body>
  
</body>
