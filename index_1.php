<?php 

  /* предварительное присвоение для отсутствия ошибок */
$today = date('Y-m-d', strtotime('today'));
$date1 = $today;
$date2 = $today;
$actual = array();

   /* ...при нажатии кнопки "получить".. */
  if($_GET) {
    /*получение данных из базы*/
    include('config_1.php');
    $date1 = $_GET['date1'];
    $date2 = $_GET['date2'];
    $i=0;
    $actual = array();
    $select = mysqli_query($conn, "SELECT * FROM `test` WHERE date>='$date1' AND date<='$date2' ORDER BY `date` DESC ");
    while ($result = mysqli_fetch_array($select)) { 
        $actual[$i][0] = $result['id'];
        $actual[$i][1] = $result['date'];
        $actual[$i][2] = $result['name'];
        $actual[$i][3] = $result['text'];
        $i++;
    } 
    mysqli_close($conn);
    }
  if($_POST) {
    include('config_1.php');
    /*создание новой строки*/
    if (isset($_POST['date_1'])){
    $date_1 = $_POST['date_1'];
    $name_1 = $_POST['name_1'];
    $mess_1 = $_POST['mess_1'];
    if ($date_1 !== null and $name_1 !== null and $mess_1 !== null ){
      mysqli_query($conn, "INSERT INTO `test` (`date`, `name`, `text`) VALUES ('$date_1', '$name_1', '$mess_1');");
       $i=0;
      $actual = array();
      $select = mysqli_query($conn, "SELECT * FROM `test` WHERE date>='$date1' AND date<='$date2' AND `show_on` IS NULL ORDER BY `date` DESC ");
      while ($result = mysqli_fetch_array($select)) { 
        $actual[$i][0] = $result['id'];
        $actual[$i][1] = $result['date'];
        $actual[$i][2] = $result['name'];
        $actual[$i][3] = $result['text'];
        $i++;
        } 
    }
  }
    /*редактировние*/
    if (isset($_POST['date_2'])){
    $date_2 = $_POST['date_2'];
    $name_2 = $_POST['name_2'];
    $mess_2 = $_POST['mess_2'];
    $id = $_POST['str_id'];
    if ($date_2 !== null and $name_2 !== null and $mess_2 !== null ){
      mysqli_query($conn, "UPDATE `test` SET date = '$date_2', name = '$name_2', text = '$mess_2' WHERE id = '$id';");
      if (isset($_POST['del']) and $_POST['del'] == 'on'){
        mysqli_query($conn, "DELETE FROM `test` WHERE id = '$id';");
       }
       $i=0;
       $actual = array();
       $select = mysqli_query($conn, "SELECT * FROM `test` WHERE date>='$date1' AND date<='$date2' ORDER BY `date` DESC ");
       while ($result = mysqli_fetch_array($select)) { 
       $actual[$i][0] = $result['id'];
       $actual[$i][1] = $result['date'];
       $actual[$i][2] = $result['name'];
       $actual[$i][3] = $result['text'];
       $i++;
       } 
     }
     }
    mysqli_close($conn);
    }
?>

<html>
<head> 

  <meta charset="utf-8" content="text/html" />
  <title> Задача </title>
  <link rel="stylesheet" type="text/css" href='style_1.css' />
</head>
<body>
<div id="head">

  <a id="a1">
  <a margin-right="20">Тест</a>
  
  </a>
</div>

  <header><h1>Заголовок</h1></header>
  <div id="left">
   <a href="#">Главная</a><br><br>
   </div>
  <div class='setting'>
     <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
      <p><br>Выберите период:<br><a> С: <input id="time" type="date" name="date1" value="<?php echo $date1; ?>"> По: </a><input id="time" type="date" name="date2" value="<?php echo $date2; ?>">
      <input id="time" type="submit" value="Получить данные"></p>
     </form>   
         <!-- форма 3 -->
     <?php if ($date1 > $date2) echo "
    <div class='attention-popup' id='popup3'>
       <div class='b-popup-content'>
        <form>
          Неверные даты!!!
         </form><br><br>
        <a class='b-container' href='javascript:PopUpHide3(3)'>Отмена</a>
      </div>
  </div> " ?>
               <!-- форма 3 --> 

    </div>

  <div class="center">

     <div class="b-container">
      <a href="javascript:PopUpShow()">Создать заявку</a>
    </div>

        <!-- форма 1 -->
    <div class="b-popup" id="popup1">
       <div class="b-popup-content">

        <form method="POST">
           Дата: <input id="time" name="date_1" type="date" required><br><br>
           Имя: <input id="time" name="name_1" type="text" required><br><br>
           Сообщение: <input id="time" name="mess_1" type="text" required><br><br>
           <input id="time" name="submit" type="submit" value="Добавить">
         </form>

        <a href="javascript:PopUpHide()">Отменить</a>
      </div>
    </div> 
            <!-- форма 1 -->


  <div>
    <?php 
    echo "<table border='1' width='100%'><tbody width='100%'>";
    echo "<tr><td class='numb'>№</td><td class='date_tab'>Дата</td><td class='fio'>ФИО</td><td class='message'>Сообщение</td>";
    $j=0;
    for ($j=0; $j<count($actual); $j++) {
      echo "<tr>"."<td class='numb'>".($j+1)."</td>";
      echo "<td class='date_tab'>".$actual[$j][1]."</td>";
      echo "<td class='fio'>".$actual[$j][2]."</td>";
      echo "<td class='message'><a href='javascript:PopUpShow2(".$actual[$j][0].")' >".$actual[$j][3]."</a>

 <!-- форма 2 -->
    <div class='b-popup' id='popup2_".$actual[$j][0]."' style='display: none;'>
       <div class='b-popup-content'>
        <form method='POST'>
          ID записи: <input id='time' name='str_id' type='text' value=".$actual[$j][0]."><br>
           Дата: <input id='time' name='date_2' type='date' value=".$actual[$j][1]."><br><br>
           Имя: <input id='time' name='name_2' type='text' value=".$actual[$j][2]."><br><br>
           Сообщение: <input id='time' name='mess_2' type='text' value=".$actual[$j][3]."><br><br>
           <label><input type='checkbox' name='del' value='on' >Удалить запись</label><br>
           <input id='time' name='submit' type='submit' value='Отправить'>
         </form>
        <a href='javascript:PopUpHide2(".$actual[$j][0].")'>Отменить</a>
      </div>
  </div> 
       <!-- форма 2 -->


      </td>";
      echo "</tr>";
    }
    echo "</tbody></table>";
    ?>
  </div>
</div>
<script src="https://code.jquery.com/jquery-2.0.2.min.js"></script>
<script src="https://stat.yagoda-group.ru/popup.js"></script>
<div id="footer">
  <a id="a1" href="mailto:marketing@yagoda-group.ru">Футер</a>
  
</div>

</body>

</html>