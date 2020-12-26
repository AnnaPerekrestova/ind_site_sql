<?php
include "ind1.php";
  //Если переменная Name передана
  if (isset($_POST["button"])) {
  	
    $sql = mysqli_query($link, "INSERT INTO  `payment` ( `customer_phone`, `map_nom`, `date_map`, `map_count`, `account`, `bank_id`) VALUES  ('{$_POST['customer_phone']}', '{$_POST['map_nom']}', '{$_POST['date_map']}', '{$_POST['map_count']}', '{$_POST['account']}', '{$_POST['bank_id']}')");
    //Если вставка прошла успешно
    if ($sql) {
      echo '<p>Данные успешно добавлены в таблицу.</p>';
      header("Location: ind_payment.php");
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
    <p>Номер квитанции:
	
    <select name="map_nom">
      <?php
      include "ind1.php";

      $acc = mysqli_query($link, "SELECT map_nom FROM accruals");
      if ($acc){
        while($a = mysqli_fetch_array($acc))
        {
          echo "<option value={$a['map_nom']}>{$a['map_nom']}</option>";
        }
      } 
      ?>
      
    </select></p>
    <p>Дата платежа:
  <input type="date" name="date_map" value="<?php echo date("Y-m-d")?>"></p>
    <p>Сумма платежа:
	<input type="number" name="map_count"  ></p>
	<p>Номер счета в банке:
	<input type="text" name="account" ></p>
	<p>Банк:
	<select name="bank_id">
      <?php
      include "ind1.php";

      $q = mysqli_query($link, "SELECT bank_id, bank FROM bank");
      if ($q){
        while($r = mysqli_fetch_array($q))
        {
          echo "<option value={$r['bank_id']}>{$r['bank']}</option>";
        }
      } 
      ?>
      
    </select></p>
	<input type="submit" name="button" value="Добавить оплату"  class="dws-submit">
	
</form>
</body>
</html>