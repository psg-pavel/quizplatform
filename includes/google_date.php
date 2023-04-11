<?
if($_POST) {
    if (isset($_POST['sourse_check'])){
		$table = 'data_'.$auth_login;
		$source_check = $_POST['sourse_check'];
		$querr = mysqli_query($conn, "SELECT * FROM `$table` WHERE source='".$source_check."' ORDER BY `$table`.`date` DESC");
		while ($resul = mysqli_fetch_array($querr)){ 
  			$last_d = $resul['date'];
    		break;
		}
	if ($last_d !== NULL) {
		echo "Последняя дата ".$source_check.": ".$last_d."<br><br>";
	} else {
		echo $source_check." не загружался <br><br>";
	}
}}
?>
    <form method='POST'>
      Последнюю дату какого источника проверить?<br><input id='time' name='sourse_check' type='text' required>
      <input id='time' name='submit' type='submit' value='Проверить'><br><br>
    </form>