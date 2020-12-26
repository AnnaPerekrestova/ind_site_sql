
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="ind_css3.css">
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="" method="post">
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
  		
  	</select>
	
    <p>Месяц начисления:
    	<select name="mon">
    		<option value="1">Январь</option>
    		<option value="2">Февраль</option>
    		<option value="3">Март</option>
    		<option value="4">Апрель</option>
    		<option value="5">Май</option>
    		<option value="6">Июнь</option>
    		<option value="7">Июль</option>
    		<option value="8">Август</option>
    		<option value="9">Сентябрь</option>
    		<option value="10">Октябрь</option>
    		<option value="11">Ноябрь</option>
    		<option value="12">Декабрь</option>
    	</select></p>
	<input type="submit" name="button" value="Поиск" class="dws-submit">
	<?php
include "ind1.php";
  //Если переменная Name передана
  if (isset($_POST["button"])) {
  	$str = mysqli_query($link, "select * from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}' AND MONTH(ac.period_start)='{$_POST['mon']}';" );
  	if ($str){
    //Вставляем данные, подставляя их в запрос
    $m = mysqli_query($link, "select MONTHNAME(ac.period_start) from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}' AND MONTH(ac.period_start)='{$_POST['mon']}';" );
    $y=mysqli_query($link, "select YEAR(ac.period_start) from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}';" );
    $addr=mysqli_query($link, "select p.phone_address from phone p WHERE p.customer_phone='{$_POST['customer_phone']}' " );
    $acc=mysqli_query($link, "select ac.accrued from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}' AND MONTH(ac.period_start)='{$_POST['mon']}';" );
$comp=mysqli_query($link, "select ac.compensation from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}' AND MONTH(ac.period_start)='{$_POST['mon']}';" );
$sum=mysqli_query($link, "select ac.summa from accruals ac WHERE ac.customer_phone='{$_POST['customer_phone']}' AND MONTH(ac.period_start)='{$_POST['mon']}';" );    //Если вставка прошла успешно
	$mon=mysqli_fetch_array($m) ;
	$month=$mon['MONTHNAME(ac.period_start)'];
	$ye=mysqli_fetch_array($y) ;
	$year=$ye['YEAR(ac.period_start)'];
	$ad=mysqli_fetch_array($addr) ;
	$address=$ad['phone_address'];
	$ac=mysqli_fetch_array($acc) ;
	$accur=$ac['accrued'];
	$c=mysqli_fetch_array($comp) ;
	$compens=$c['compensation'];
	$s=mysqli_fetch_array($sum) ;
	$summa=$s['summa'];
	echo "<h3>Квитанция на оплату</h3> <p>за {$month} {$year} по номеру телефона {$_POST['customer_phone']} расположенному по адресу {$address}</p> 
	<table border=1>
	<tr><td> Начисленно:  </td><td> {$accur }</td></tr>
	<tr><td>Вычеты (с учетом времени не работы телефона и льгот): </td><td>{$compens}</td></tr>
	<tr><td> Итого к уплате:  </td><td> {$summa }</tr>
	 </table> ";
	
	
  /*echo is_string($y);
  echo is_string($addr);
  echo is_string($acc);
  echo is_string($comp);
  echo is_string($sum);*/
  
}
else
{
	echo "Такой записи нет";
  echo "<p><b>Error: ".mysqli_error($link)."</b><p>";
  exit();
}
  }
  else
  {
  	$month="месяц" ;
	$ye="год";
	$ad="адрес" ;
	$ac= "сумма" ;
	$c="сумма" ;
	$s="сумма";
	$_POST['customer_phone']="телефон";
  }
?>
	
</form>
</body>
</html>