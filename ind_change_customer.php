<!-- <?php
    include 'ind_customer_table.php';  
?> -->
<?php
$id= $_GET['id'];
$cust = mysqli_query($link, "select * from customer where customer_id='$id'; ");
while ($customers = mysqli_fetch_array($cust)){

$id =$customers['customer_id'];
$type = $customers['customer_type'];
$fio= $customers['customer_fio'];
$name= $customers['customer_name'];
$room= $customers['customer_room'];
$chief = $customers['chief'];
  //<td> <INPUT name="button_'.$customers['customer_id'].'" id="button_'.$customers['customer_id'].'" type="submit" value="Узнать подробнее"></td>
 } 
if (isset($_POST['button'])){
mysqli_query($link, "UPDATE customer SET customer_type = '{$_POST['type']}', customer_fio = '{$_POST['fio']}',customer_name = '{$_POST['name']}',customer_room = '{$_POST['room']}', chief = '{$_POST['chief']}' where customer_id='$id'; ");
print("Данные успешно изменены");

header("Location: ind_customer_table.php");

			exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="ind_css3.css">
	<title>Изменение данных клиента</title>
</head>
<body>
	<form action="" method="post">
  <p>Тип клиента:
  	<select name="type">
      <?php
      include "ind1.php";

      $ex = mysqli_query($link, "SELECT exempt_type, name FROM exempt_table");
      if ($ex){
        while($r = mysqli_fetch_array($ex))
        {
          echo "<option value={$r['exempt_type']}>{$r['name']}</option>";
        }
      } 
      ?>
      
    </select></p>
    <p>ФИО (для физических лиц):
	<input type="text" name="fio" value="<?php echo $fio?>"></p>
    <p>Название отдела (для юридических лиц):
	<input type="text" name="name" value="<?php echo $name?>"></p>
    <p>Номер комнаты:
	<input type="number" name="room" value="<?php echo $room?>"></p>
	<p>Руководитель отдела:
	<input type="text" name="chief"  value="<?php echo $chief?>"></p>
	<input type="submit" name="button" value="Изменить" class="dws-submit">
	
</form>
</body>
</html>
