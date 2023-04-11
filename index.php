<?php 
include_once(__DIR__.'/library.php');
include_once(DIR_INCLUDES.'functions.php');
  /* предварительное присвоение для отсутствия ошибок */
$yesterday = date('Y-m-d', strtotime('yesterday'));
$date1 = $yesterday;
$date2 = $yesterday;
$actual = array();
$standart = array("Источник рекламы", "Тип трафика", "Кампания", "Группа", "Фраза", "Тип устройства");
$groupsmassive = array(0, 1, 2, 3, 4, 5);
if (isset($_COOKIE['groupsmassive'])){
  $groupsmassive = explode(',', $_COOKIE['groupsmassive']);
}
   /* ...при нажатии кнопки "получить".. */
  if($_POST) {
    /*- обновление данных в базе -*/
    include('auth/check.php');
    if ($auth_role !== '1' & $auth_role !== '0' & $auth_login !== null & $auth_counter !== null){
       require_once (DIR.'direct.php');}
    /*получение данных из базы*/
    if ($auth_login !== null){
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $login_q = $auth_login;
    $login_querry = "data_".$auth_login;
    $i=0;
    $actuals = array();
    /* получаем данные по столбцам за период */
    $select = mysqli_query($conn, "SELECT * FROM `$login_querry` WHERE date>='$date1' AND date<='$date2'");
    while ($result = mysqli_fetch_array($select)) { 
        $actuals[array_search(0, $groupsmassive)][$i] = $result['source'];
        $actuals[array_search(1, $groupsmassive)][$i] = trafic_type($result['medium']);
        $actuals[array_search(2, $groupsmassive)][$i] = $result['campaign'];
        $actuals[array_search(3, $groupsmassive)][$i] = $result['content'];
        $actuals[array_search(4, $groupsmassive)][$i] = $result['term'];
        $actuals[array_search(5, $groupsmassive)][$i] = device_type($result['device']);
        $actuals[6][$i] = $result['clicks'];
        $actuals[7][$i] = $result['goals'];
        $actuals[8][$i] = $result['cost'];
        $i++;
    } 
    $sourcelist = array();
    mysqli_close($conn);
    }
    else {
      popup(2);
    }
  }
 /* сортируем и переводим в строки */
    if (isset($actuals[0])) {
    array_multisort($actuals[0], SORT_ASC, $actuals[1], SORT_ASC, $actuals[2], SORT_ASC, $actuals[3], SORT_ASC, $actuals[4], SORT_ASC, $actuals[6], SORT_DESC, $actuals[7], SORT_DESC, $actuals[8], SORT_DESC);
    for ($j=0; $j<count($actuals[0]); $j++) {
        $actual[$j][0] = $actuals[0][$j];
        $actual[$j][1] = $actuals[1][$j];
        $actual[$j][2] = $actuals[2][$j];
        $actual[$j][3] = $actuals[3][$j];
        $actual[$j][4] = $actuals[4][$j];
        $actual[$j][5] = $actuals[6][$j];
        $actual[$j][6] = $actuals[7][$j];
        $actual[$j][7] = $actuals[8][$j];
    }
  }
         /* подсчет итоговых значений */
    $allclicks = 0;
    $allgoals = 0;
    $allcost = 0;
    for ($l=0; $l<count($actual); $l++) {
        $allclicks += $actual[$l][5];
        $allgoals += $actual[$l][6];
        $allcost += $actual[$l][7];
    }
       
?>

<html>
<head> 
    <script type="text/javascript">
        function sh(el) {
        var row = el.parentNode.parentNode.parentNode;
        var nextRow = row.parentNode.rows[row.rowIndex + 1];
        var chk = nextRow.querySelector('td > input');
        chk.checked = !chk.checked;
        }

        function SwapAll(b) {
        var tbl = document.getElementById('MyTable');
        var chks = tbl.getElementsByTagName('input');
        for (var i=0; i<chks.length; i++) {
            chks[i].checked = b;
            }
        }
    </script>
  <meta charset="utf-8" content="text/html" />
  <title> Simple Stat </title>
  <link rel="stylesheet" type="text/css" href='styles.css' />
</head>
<body>
  <? include_once(DIR_INCLUDES.'header.php'); ?>
  <? include_once(DIR_INCLUDES.'menu.php'); ?>
  <? include_once(DIR_INCLUDES.'groups_popup.php'); ?>


  <div class="center">
  <div class='setting'>
     <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <p>Выберите период:<br><a> С: <input id="time" type="date" name="date1" value="<?php echo $date1; ?>"> По: </a><input id="time" type="date" name="date2" value="<?php echo $date2; ?>">
      <input id="time" type="submit" value="Получить данные"></p>
     </form>    
                 <!-- попап неверные даты -->
     <?php if ($date1 > $date2) 
        popup(0);
      ?>
               <!--  --> 
    </div>
    <h3>Табличные данные</h3>
    <button onclick="Groups()">Группировка</button> &#160; <button onclick="SwapAll(false);">Свернуть всё</button> &#160; <button onclick="SwapAll(true);">Развернуть всё</button>
    <table id="MyTable">
  
    <tr><?
        /*заголовки по стандарту*/
        for ($st=0; $st<5; $st++){
            echo "<th>".$standart[$groupsmassive[$st]]."</th>";    
        }
  ?><th>Клики</th><th>Заявки</th><th>Стоимость</th><th>Цена заявки</th></tr>

  <tr><th colspan="5">ИТОГО</th><th><?php echo $allclicks;?></th><th><?php echo $allgoals;?></th><th><?php echo $allcost;?></th><th><?php if ($allgoals == 0) {echo '--';} else {echo round(($allcost/$allgoals),2);}?></th></tr>
  
    <?php 
    for ($i1=0; $i1<count($actual); $i1++) {
        if (!in_array($actual[$i1][0], $sourcelist, true)) {
            echo ('
              <tr style="background-color:#ffffff"><td><label><input type="checkbox"><a onclick="sh(this)">'
              .$actual[$i1][0].
              '</a></label></td><td></td><td></td><td></td><td></td>
              <td>'.sum_data($actual, $actual[$i1][0], 1, $i1, 5).'</td>
              <td>'.sum_data($actual, $actual[$i1][0], 1, $i1, 6).'</td>
              <td>'.sum_data($actual, $actual[$i1][0], 1, $i1, 7).'</td><td>');
            if ((sum_data($actual, $actual[$i1][0], 1, $i1, 6)) == 0) {
                echo ('--');
            } else {
                echo ( round((( sum_data($actual, $actual[$i1][0], 1, $i1, 7))/(sum_data($actual, $actual[$i1][0], 1, $i1, 6))), 2).'</td></tr>');
            }
            echo ('<tr><td colspan=5 class="node"><input type="checkbox">');

            $mediumlist = array();
            for ($i2=$i1; $i2<count($actual); $i2++) {
                if ($actual[$i2][0] == $actual[$i1][0] & !in_array($actual[$i2][1], $mediumlist, true)) {
                    echo ('
                      <table>
                      <tr style="background-color:#dee0e0"><td></td><td><label><input type="checkbox"><a onclick="sh(this)">'
                      .$actual[$i2][1].
                      '</a></label></td><td></td><td></td><td></td>
                      <td>'.sum_data($actual, $actual[$i2][1], 2, $i2, 5).'</td>
                      <td>'.sum_data($actual, $actual[$i2][1], 2, $i2, 6).'</td>
                      <td>'.sum_data($actual, $actual[$i2][1], 2, $i2, 7).'</td><td>');
                      if ((sum_data($actual, $actual[$i2][1], 2, $i2, 6)) == 0) {
                                                echo ('--');
                            } else {
                                echo ( round((( sum_data($actual, $actual[$i2][1], 2, $i2, 7))/(sum_data($actual, $actual[$i2][1], 2, $i2, 6))), 2).'</td></tr>');
                            }
                      echo ('<tr><td colspan=5 class="node"><input type="checkbox">');

                    $campaignlist = array();    
                    for ($i3=$i2; $i3<count($actual); $i3++) {
                        if ($actual[$i3][1] == $actual[$i2][1] & $actual[$i3][0] == $actual[$i1][0] & !in_array($actual[$i3][2], $campaignlist, true)) {
                              echo ('
                                <table>
                                <tr style="background-color:#d1d1d1"><td></td><td></td><td><label><input type="checkbox"><a onclick="sh(this)">'
                                .$actual[$i3][2].
                                '</a></label></td><td></td><td></td>
                                <td>'.sum_data($actual, $actual[$i3][2], 3, $i3, 5).'</td>
                                <td>'.sum_data($actual, $actual[$i3][2], 3, $i3, 6).'</td>
                                <td>'.sum_data($actual, $actual[$i3][2], 3, $i3, 7).'</td><td>');
                            if ((sum_data($actual, $actual[$i3][2], 3, $i3, 6)) == 0) {
                                                echo ('--');
                            } else {
                                echo ( round((( sum_data($actual, $actual[$i3][2], 3, $i3, 7))/(sum_data($actual, $actual[$i3][2], 3, $i3, 6))), 2).'</td></tr>');
                            }
                                echo ('<tr><td colspan=5 class="node"><input type="checkbox">');
                            
                            $contentlist = array();        
                            for ($i4=$i3; $i4<count($actual); $i4++) {
                                if ($actual[$i4][2] == $actual[$i3][2] & $actual[$i4][1] == $actual[$i2][1] & $actual[$i4][0] == $actual[$i1][0] & !in_array($actual[$i4][3], $contentlist, true)) {
                                    echo ('            
                                    <table>
                                    <tr style="background-color:#c2c2c2"><td></td><td></td><td></td><td><label> <input type="checkbox"><a onclick="sh(this)">'
                                    .$actual[$i4][3].
                                    '</a></label></td><td></td>
                                    <td>'.sum_data($actual, $actual[$i4][3], 4, $i4, 5).'</td>
                                    <td>'.sum_data($actual, $actual[$i4][3], 4, $i4, 6).'</td>
                                    <td>'.sum_data($actual, $actual[$i4][3], 4, $i4, 7).'</td><td>');
                                    if ((sum_data($actual, $actual[$i4][3], 4, $i4, 6)) == 0) {
                                                echo ('--');
                                    } else {
                                                echo (round (((sum_data($actual, $actual[$i4][3], 4, $i4, 7))/(sum_data($actual, $actual[$i4][3], 4, $i4, 6))),2).'</td></tr>');
                                              }
                                    echo ('<tr><td colspan=5 class="node"><input type="checkbox">');
               
                                    $termlist = array();
                                    for ($i5=$i4; $i5<count($actual); $i5++) {
                                        if ($actual[$i5][3] == $actual[$i4][3] & $actual[$i5][2] == $actual[$i3][2] & $actual[$i5][1] == $actual[$i2][1] & $actual[$i5][0] == $actual[$i1][0] & !in_array($actual[$i5][4], $termlist, true)) {
                                            echo ('               
                                            <table>
                                            <tr style="background-color:#b8b8b8"><td></td><td></td><td></td><td></td><td>'.$actual[$i5][4].'</td>
                                            <td>'.sum_data($actual, $actual[$i5][4], 5, $i5, 5).'</td>
                                            <td>'.sum_data($actual, $actual[$i5][4], 5, $i5, 6).'</td>
                                            <td>'.sum_data($actual, $actual[$i5][4], 5, $i5, 7).'</td><td>'); 
                                            if ((sum_data($actual, $actual[$i5][4], 5, $i5, 6)) == 0) {
                                                echo ('--');
                                            } else {
                                                echo (round(((sum_data($actual, $actual[$i5][4], 5, $i5, 7))/(sum_data($actual, $actual[$i5][4], 5, $i5, 6))), 2).'</td></tr>');
                                            } 
                                             echo ('</table>');
                                        } array_push($termlist, $actual[$i5][4]);
                                    } unset($termlist);
                                    echo ('</td></tr></table>');
                                } array_push($contentlist, $actual[$i4][3]);
                            } unset($contentlist);
                            echo ('</td></tr></table>');
                        } array_push($campaignlist, $actual[$i3][2]);
                    } unset($campaignlist);
                    echo ('</td></tr></table>');
                } array_push($mediumlist, $actual[$i2][1]);
            } unset($mediumlist);
        }
        array_push($sourcelist, $actual[$i1][0]);
    } unset($sourcelist);
    echo ('</table>');

    ?>
 <br><br>   
</div>
<? include_once(DIR_INCLUDES.'footer.php'); ?>
</body>
</html>