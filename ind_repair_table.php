<?php
include "ind1.php";
$rep = mysqli_query($link, "select * from repairs;");
if($rep)
{
  echo '<form name="feedback" action="" method="post">';
  // Определяем таблицу и заголовок
  echo "<table border=1>";
  echo "<tr><td>Номер заявки</td><td>Дата заявки</td><td>Дата восстановления</td><td>Фамилия принявшего заявку</td><td>Номер телефона клиента</td></tr>";
  // Так как запрос возвращает несколько строк, применяем цикл
  while($r = mysqli_fetch_array($rep))
  {
    echo "<tr><td>".$r['number_claim']."&nbsp;</td><td>".$r['date_claim']."&nbsp </td><td>".$r['date_repair']."&nbsp;</td><td>" .$r['inspector']."&nbsp;</td><td>".$r['customer_phone']."&nbsp;</td></tr>";
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
  <title>Таблица ремонтов</title>
</head>
<body>
  
</body>
</html>