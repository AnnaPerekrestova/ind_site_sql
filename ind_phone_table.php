<?php
include "ind1.php";
$p = mysqli_query($link, "select p.customer_phone, c.customer_fio, c.customer_name,p.limit_value,p.phone_address,p.value from phone p INNER JOIN customer c ON c.customer_id=p.customer_id;");
if($p)
{
  echo '<form name="feedback" action="" method="post">';
  // Определяем таблицу и заголовок
  echo "<table border=1>";
  echo "<tr><td>Номер телефона клиента</td><td>Клиент-владелец</td><td>Адрес</td><td>Лимит</td><td>Абонентская плата</td><td>Долг по оплате</td></tr>";
  // Так как запрос возвращает несколько строк, применяем цикл
  while($c = mysqli_fetch_array($p))
  {
    $summ = mysqli_query($link, "select  sum(a.summa) FROM accruals a WHERE a.customer_phone='{$c['customer_phone']}';");
    while($s = mysqli_fetch_array($summ))
      $d= $s['sum(a.summa)'];
    
    $summ_p = mysqli_query($link, "select  sum(p.map_count) FROM payment p WHERE p.customer_phone='{$c['customer_phone']}';");
    while($s_p = mysqli_fetch_array($summ_p))
      $d=$d-$s_p['sum(p.map_count)'];
    
    if($c['customer_fio'])
    {
       echo "<tr><td>".$c['customer_phone']."&nbsp;</td><td>".$c['customer_fio']."&nbsp </td><td>".$c['phone_address']."&nbsp;</td><td>".$c['limit_value']."&nbsp;</td><td>".$c['value']."&nbsp;</td><td>".$d."&nbsp;</td></tr>";
    }
    else
    {
      echo "<tr><td>".$c['customer_phone']."&nbsp;</td><td>".$c['customer_name']."&nbsp </td><td>".$c['phone_address']."&nbsp;</td><td>".$c['limit_value']."&nbsp;</td><td>".$c['value']."&nbsp;</td><td>".$d."&nbsp;</td></tr>";
    }

   
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
  <title>Таблица телефонов</title>
</head>
<body>
  
</body>
</html>