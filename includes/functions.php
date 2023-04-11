<?
include_once($_SERVER['DOCUMENT_ROOT'].'/auth/config.php');
// Соединямся с БД
if(isset($_POST['login_submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняется введенному
    $query = mysqli_query($conn,"SELECT user_hash, password FROM access WHERE login='".mysqli_real_escape_string($conn,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    // Сравниваем пароли
    if($data['password'] === $_POST['password'])
    {
        $hash = $data['user_hash'];
       // Ставим куки
        setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!!
        header("Location: ../index.php"); exit();
    }
    else
    {
        popup(5);
    }
 }
/* поиск суммы любого уровня */
function sum_data ($arr, $key, $level, $numstr, $numcol) {
    $result = 0;
    for ($i=$numstr; $i<count($arr); $i++) {
        for ($j=0; $j<$level; $j++) {
            if ($arr[$i][$j] !== $arr[$numstr][$j]) {
              $j = $level;
            }
            elseif ($j == $level-1) {
              $result += $arr[$i][$numcol];
            }
        }
    }
    return $result;
}

/* русифицирование типа трафика */
function trafic_type ($string)
{
    if ($string == "AD_NETWORK") {
          return 'РСЯ';
    } elseif ($string == "SEARCH") {
          return 'Поиск';
    } else {
          return $string;
    }
}
function device_type($str)
{
    $type = $str;
    if (!$str){
        $type = "Не определено";
    }
    return $type;
}

/*очистка массива из метрики*/
function goals_clear($goals_array)
{
    $n_goals = array();
    if ($goals_array !== NULL) {
    foreach ($goals_array as $i){
        $z = (count($i['metrics']))-1;
        $gg = 0;
        while ($z>0){
            $gg += $i['metrics'][$z];
            unset($i['metrics'][$z]);
            $z--;
        }
        if ($gg > 0 ) {
            $i['metrics'][0] = $gg;
            array_push($n_goals, $i);
        }}}
        return $n_goals;
}

 /* ---сравнение имен групп--- */ 
function groops_compare($x,$y)
{  
    $numgroop_x = explode("_", $x);
    if ($numgroop_x[0] == $y) {
        return true;
    } else {
        return false;
    }
}
/* очистка ключей */
function key_from_str($str)
{
    $pos = strripos($str, "_");
    if ($pos !== false) {
     $words = explode("_", $str);
     return $words[1];
    }
}
function key_clear($key)
{
    $key_1 = str_replace('!', '', $key);
    $key_2 = str_replace('/', '', $key_1);
    $key_3 = str_replace('+', '', $key_2);
    $key_cl = str_replace('"', '', $key_3);
    $pos = strpos($key_cl, '-');
    if ($pos !== false) {
        $res = substr($key_cl, 0, ($pos - 0));
    } else {
        $res = $key_cl;
    }
    return $res;
}

/* очистка ключей для проверки*/
function key_full_clear($key)
{
    $key_1 = key_clear($key);
    $res = str_replace(' ', '', $key_1);
    return $res;
}
 /* ---сравнение ключей--- */ 
function key_compare($x,$y)
{  
    if (key_full_clear(key_from_str($x)) == key_full_clear($y)) {
        return true;
    } else {
        return false;
    }

}
/*--- получение количества заявок по строке из метрики ---*/
function goals_fusion($goal_string, $direct_arr) 
{
    $goals = array();
    if ($direct_arr !== NULL) {
        for ($d=0; $d<count($direct_arr); $d++) {
            if ($goal_string['dimensions'][0]['name'] == $direct_arr[$d][0] & groops_compare($goal_string['dimensions'][1]['name'], $direct_arr[$d][7]) & key_compare($goal_string['dimensions'][1]['name'], $direct_arr[$d][4])) {
                array_push($goals, TRUE, $d);
                break;
            } elseif ($base_string[8] == 'RETARGETING' or $base_string[8] == 'FEED_FILTER' or $base_string[8] == 'WEBPAGE_FILTER' or $base_string[8] == 'AUTOTARGETING') {
                if ($q['dimensions'][0]['name'] == $base_string[0] & groops_compare($q['dimensions'][1]['name'], $base_string[7])) {
                    array_push($goals, TRUE, $d);
                    break;
                }
            } 
        }
    }
    return $goals;
}

/*--- нахождение всех полей для разбора оставшихся лидов ---*/
function goals_fusion3($goal_string, $direct_arr) 
{
    $fields = array();
    if ($direct_arr !== NULL) {
        for ($d=0; $d<count($direct_arr); $d++) {
            if (groops_compare($goal_string['dimensions'][1]['name'], $direct_arr[$d][7])) {
                array_push($fields, TRUE, $direct_arr[$d][1], $direct_arr[$d][2], $direct_arr[$d][3]);
                break;
            } 
        }
    }
    return $fields;
}

/* нахождение крайней даты в БД */
function last_date($conn, $login_querry)
{
    $last = mysqli_query ($conn, "SELECT * FROM `$login_querry` ORDER BY `$login_querry`.`date` DESC" );
        while ($res = mysqli_fetch_array($last)){ 
            $last_date = $res['date'];
            break;
        }
    return $last_date;    
}
function goals_metrics($goals_arr){
    $query_string = "";
    foreach ($goals_arr as $goal_num){
        if ($goal_num !== NULL) {
        $query_string .= ",ym:s:goal".$goal_num."visits";}
    }
    return $query_string;
}


/* на setting */
function no_dots_login($log_name)
{
    $no_dots=str_ireplace(".", "|", $log_name);
    return $no_dots;
}
function cost_fix($cost_broken, $cours)
{
    $firs=str_ireplace(",", ".", $cost_broken);
    $sec=round(($firs * 1.2 * $cours),2);
    return $sec;
}
function key_clear2($key)
{
    $key_1 = str_replace('!', '', $key);
    $key_2 = str_replace('/', '', $key_1);
    $key_3 = str_replace('+', '', $key_2);
    $key_cl = str_replace('"', '', $key_3);
    $pos = strpos($key_cl, '-');
    if ($pos !== false) {
        $res = substr($key_cl, 0, ($pos - 0));
    } else {
        $res = $key_cl;
    }
    return $res;
}

  function dots_login ($log_name)
{
    $dots=str_ireplace("|", ".", $log_name);
    return $dots;
}
  function goal_list ($arr_goal)
{
    $one_goal = explode(" ", $arr_goal);
    return $one_goal;
}
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}
function get_main_array($main_string, $marker) {
    $first = explode("\r", $main_string);
    $second = array();
    $errs = 0;
    $i=0;
    foreach($first as $stroka){
        if (($i+1) !== count($first)){
            array_push($second, (explode("\t", $stroka)));
            if ($marker == 1 and count($second[$i]) !== 9){
                $errs++;
            } elseif ($marker == 2 and count($second[$i]) !== 3) {
                $errs++;
            }
        }
        $i++;
    }
    if ($errs !== 0) {
        echo $errs." ошибок!";
        popup(4);
        } else {
            return $second;
            }
}
function date_fix($broken_date) {
    $no_tab = str_ireplace("    ", "", $broken_date);
    $no_space = str_ireplace(" ", "", $no_tab);
    $no_10 = str_ireplace(chr(10), "", $no_space);
    $ar_string = explode(".", $no_10);
    if (count($ar_string) == 3 and strlen($ar_string[0]) == 2 and strlen($ar_string[1]) == 2 and strlen($ar_string[2]) == 4){
        $fix_date = $ar_string[2].".".$ar_string[1].".".$ar_string[0];
        return $fix_date;
        } elseif (strpos($no_10, '-') == 4 and strripos($no_10, '-') == 7 and strlen($no_10) == 10) {
            $fix_date = str_ireplace("-", ".", $no_10);
            return $fix_date;
        } else {
            popup(3);
        }

    }
function groops_compare2($x,$y)
{  
    $numgroop_x = explode("_", $x);
    if ($numgroop_x[0] == $y) {
        return true;
    } else {
        return false;
    }
}
function key_from_str2($str)
{
    $pos = strripos($str, "_");
    if ($pos !== false) {
     $words = explode("_", $str);
     return $words[1];
    }
}
function key_full_clear2($key)
{
    $key_1 = key_clear2($key);
    $res = str_replace(' ', '', $key_1);
    return $res;
}
function key_compare2($x,$y)
{  
    if (key_full_clear2(key_from_str2($x)) == key_full_clear2($y)) {
        return true;
    } else {
        return false;
    }

}
function goals_fusion_2($base_string, $metrika_arr) {
    $goals = 0;
    if (isset($metrika_arr[0][0])) {
    foreach ($metrika_arr as $q) {
        if (date_fix($q[0]) == date_fix($base_string[0]) & groops_compare2($q[1], $base_string[8]) & key_compare2($q[1], $base_string[5])) {
            $goals += $q[2];    
        }
    }
    }
    return $goals;
}
function not_null_echo($value) {
    if ($value !== NULL){
            $res = $value;
            } else {
                $res = "- не подключено -";
            }
    return $res;
}
function not_null_str ($str) {
    $res = false;
    $str = preg_replace('/\s+/', '', $str);;
    if ($str !== ''){
        $res = true;
    }
    return $res;
}
function popup($select) {
    $list = array(
        "Неверные даты !!!",
        "Вы не администратор ((",
        "ВЫ НЕ АВТОРИЗОВАНЫ !!!",
        "Неверный формат даты ",
        "При загрузке обнаружены ошибки",
        "Неверный логин или пароль",
        "Такая цель уже есть",
        "Такой цели НЕТ",
        "Введён тот же счетчик!!!",
        "Введён тот же ID !!!",
        "Введён тот же логин !!!",
        "Введен неверный логин!!!",
        "Такого источника или интевала дат - не существует!!!",
        "Пользователя с таким логином НЕТ в базе данных",
        "Неверна указана цель! Её нет в метрике!",
        "Этого пользователя нельзя удалять!",
        "Имя не может быть пустым"
    );
    echo "
    <div class='attention-popup' id='popup3'>
       <div class='b-popup-content'>
        <form><br><br>
          ".$list[$select]."
         </form>
      </div>
    </div> ";
}
function popup4($num_del, $name_del) {
    echo "
    <div class='attention-popup' id='popup4'>
       <div class='b-popup-content'>
        <form method='POST'><br><br>
            <input name='login_del_final' value='".$num_del."'><br>
            Удалить полностью ".$name_del." ? ".$num_del."<br><br><br>
            <button id='time' name='submit' type='submit' >удалить</button><br><br>
            <a href='javascript:del_Hide(".$num_del.")'>ОТМЕНА</a><br><br><br>
         </form>
      </div>
    </div> ";
    
} 
function goal_check($goal_id, $count_id){
if ($count_id !== NULL) {
        $sURL = 'https://api-metrika.yandex.net/stat/v1/data?ids='.$count_id.'&metrics=ym:s:visits,ym:s:goal'.$goal_id.'visits&date1=yesterday&date2=yesterday&dimensions=ym:s:date&limit=100000'; // URL-адрес GET
        $aHTTP = array(
            'http' => // Обертка, которая будет использоваться
                array(
                    'ignore_errors' => true,
                    'method' => 'GET', // Метод запроса
                    // Ниже задаются заголовки запроса
                    'header' => 'Authorization: OAuth AgAAAAA5S1H3AAY2Gcc-ALgaekTjsX3lVKB3GXo'
                )
        );
        $context = stream_context_create($aHTTP);
        $data = json_decode(file_get_contents($sURL, false, $context));
        $dat = $data -> data;
        $answ = json_decode(json_encode($dat), true);
        if ($answ !== null){
            return true;
        }
        else {return false;}
    } else {return false;}
}
  function print_goal_list ($arr_goal)
{
    $i = 0;
    if ($arr_goal !== NULL) {
        while ($i<>count($arr_goal)){
           $res .= $arr_goal[$i];
           $res .= "<br>";
          $i++;
        }} else {
            $res = "- не подключено -";
        }
    if ($i < 4){
        $i = 130;
    } else {
        $i = 80 + (($i - 1)*23);
    }
    $full_res = array($res, $i);
    return $full_res;
}
function print_row($type, $row_name, $auth_id, $form_name, $num) {
    if ($type == 1){
    echo "
    <div class='row_table'>
        <div>
            <div class='td_table'>
                <p onclick='Support(".$num.")'>".$row_name." <sup class='tooltip'>(?)<t class='tooltiptext'>Справка про ".$row_name."</t></sup>:</p>
                <h3>".$auth_id."</h3>
            </div>
        </div>
        <div style='padding: 5px;'>
         <form method='POST'>
                <h4>Изменить ".$row_name.":<br>
                    <input id='time' name=".$form_name." type='text'>
                    <input id='time' name='submit' type='submit' value='Отправить'>
                </h4>
            </form>
        </div>
    </div>";
    }
    if ($type == 2){
    echo "
    <div class='row_table' style='height: ".$auth_id[1]."px !important;'>
        <div>
            <div class='td_table'>
                <p onclick='Support(".$num.");'>".$row_name." <sup class='tooltip'>(?)<t class='tooltiptext'>Справка про ".$row_name."</t></sup>:</p>
                <h3>".$auth_id[0]."</h3>
            </div>
        </div>
        <div style='padding: 5px;'>
            <form method='POST'>
                <h4>Добавить ".$row_name.":<br>
                    <input id='time' name=add_".$form_name." type='text'>
                    <input id='time' name='submit' type='submit' value='Отправить'>
                </h4>
            </form>
            <form method='POST'>
            <h4>Удалить ".$row_name.":<br>
                <input id='time' name=del_".$form_name." type='text'>
                <input id='time' name='submit' type='submit' value='Отправить'>
            </h4>
            </form>
        </div>
    </div>";
    }
    if ($type == 3){
    echo "
    <div class='row_table'>
        <div>
            <div class='td_table'>
                <p>".$row_name."</p>
                <h3>".$auth_id."</h3>
            </div>
        </div>
        <div style='padding: 5px;'>
         <form method='POST'>
                <h4>Изменить имя в системе:<br>
                    <input id='time' name=".$form_name." type='text' value='".$num."'>
                    <input id='time' name='submit' type='submit' value='Отправить'>
                </h4>
            </form>
        </div>
    </div>";
    }
}
function rewrite_standart($new_stan, $old_stan) {
    $rewrited = array();
    if (is_array($new_stan) & count($new_stan) == 6){
        for ($l=0; $l<6; $l++){
            $rewrited[$l] = $old_stan[$new_stan[$l]]; 
        }
    }
    return $rewrited;
}
function popupShowLogin($id, $name, $login, $pass){
    echo "
    <div id='id".$id."' class='attention-popup' style='display: none;'>
       <div class='b-popup-content'>
       <div class='support-text'><br>
        Клиент: ".$name."<br>
        Логин: ".$login."<br>
        Пароль: ".$pass."<br>
        <br><br><a href='javascript:ShowLogin_hide(".$id.")'>ЗАКРЫТЬ</a>
      </div></div>
    </div> ";
}
?>