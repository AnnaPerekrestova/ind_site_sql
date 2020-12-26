<?php
include "ind1.php";
  //Если переменная Name передана
  if (isset($_POST["button"])) {
  	
    $sql = mysqli_query($link, "INSERT INTO  `calls` ( `customer_phone`, `date_ring`, `ring_type`, `number`, `country`, `town`, `value_min`) VALUES  ('{$_POST['customer_phone']}', '{$_POST['date_ring']}', '{$_POST['ring_type']}', '{$_POST['number']}', '{$_POST['country']}', '{$_POST['town']}', '{$_POST['value_min']}')");
    //Если вставка прошла успешно
    if ($sql) {
      echo '<p>Данные успешно добавлены в таблицу.</p>';
      header("Location: ind_call_table.php");
      exit();
    } else {
      echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      echo '<p>Для данного пользователя в указанном месяце лимит превышен!</p>';
    }

  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="ind_css3.css">
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="" method="post" >
  <p>Номер телефона клиента:
    <select name="customer_phone">
      <?php
      include "ind1.php";

      $q = mysqli_query($link, "SELECT customer_phone FROM phone");
      if ($q){
        while($r = mysqli_fetch_array($q))
        {
          echo "<option value={$r['customer_phone']}>{$r['customer_phone']}</option>";
        }
      } 
      ?>
      
    </select></p>
    <p>Дата звонка:
	<input type="date" name="date_ring" autofocus value="<?php echo date("Y-m-d")?>"></p>
    <p>Тип звонка:<select name="ring_type">
      <?php
      include "ind1.php";

      $ex = mysqli_query($link, "SELECT ring_type, name FROM rates");
      if ($ex){
        while($r = mysqli_fetch_array($ex))
        {
          echo "<option value={$r['ring_type']}>{$r['name']}</option>";
        }
      } 
      ?>
      
    </select></p>
    <p>Вызываемый номер:
	<input type="text" name="number" autofocus ></p>
	<p>Страна:
	<input type="text" name="country" autofocus ></p>
	<p>Город:
	<input type="text" name="town" autofocus></p>
	<p>Продолжительность звонка (мин.):
	<input type="number" name="value_min" autofocus></p>
	<input type="submit" name="button" value="Добавить запись"  class="dws-submit">
	
</form>
</body>
</html>