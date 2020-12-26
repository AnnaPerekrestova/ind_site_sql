<!-- <?php
    include 'ind_customer_table.php';  
?> -->
<?php 

function Table_calls($phone)
  {
    include 'ind1.php';
    echo "<p>С номера {$phone} были совершены звонки: </p>";
    $call = mysqli_query($link, "SELECT c.call_id,c.customer_phone, c.date_ring,r.name,c.number,c.country,c.town,c.value_min,c.summa_min,c.summa from calls c INNER JOIN rates r ON r.ring_type=c.ring_type where c.customer_phone='$phone';");
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
  }
    
$id=$_GET['id'];
echo "</table>";
$ph=mysqli_query($link, "SELECT p.customer_phone FROM phone p WHERE p.customer_id='$id'");
          
$name = mysqli_query($link, "SELECT customer_fio, customer_name FROM customer WHERE customer_id='$id'");

      //echo "<tr><td>Номер заявки</td><td>Дата заявки</td><td>Дата восстановления</td><td>Фамилия принявшего заявку</td><td>Номер телефона клиента</td></tr>";
      // Так как запрос возвращает несколько строк, применяем цикл
while($n = mysqli_fetch_array($name))
{ 
  if (!$n['customer_fio'])
  {
    echo "<p>Информация об отделе (юридическом лице) {$n['customer_name']}</p>";
  }
  else
  {
    echo "<p>Информация о физическом лице {$n['customer_fio']}</p>";
  }
}

echo "<p>Данный клиент является владельцем данных телефонов: </p>";
echo "<table border=1>";
while($p = mysqli_fetch_array($ph))
{
  echo "<tr><td>".$p['customer_phone']."</td></tr>";
  //Table_calls($p['customer_phone']);
}
echo "</table>";
$phn=mysqli_query($link, "SELECT p.customer_phone FROM phone p WHERE p.customer_id='$id'");
while($p = mysqli_fetch_array($phn))
{
  Table_calls($p['customer_phone']);
}
          
          
      
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Информация о клиенте</title>
  <link rel="stylesheet" type="text/css" href="ind_css3.css">
</head>
<body>
  
</body>
</html>