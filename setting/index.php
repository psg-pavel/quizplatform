<?
  include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
  include_once(DIR_INCLUDES.'functions.php');
  include_once(DIR_AUTH.'config.php');
    if($_POST) {
    if (isset($_POST['name'])){
    $err = [];
    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";}

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($conn, "SELECT id FROM `access` WHERE login='".mysqli_real_escape_string($conn, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {$err[] = "Пользователь с таким логином уже существует в базе данных";}

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $login = no_dots_login($_POST['login']);
        $client_name = $_POST['name'];
        $date_start = $_POST['date_start'];
        $new_hash = generateCode(10);
        $password = $_POST['password'];
          /*добавляю строку в access*/
        mysqli_query($conn,"INSERT INTO `access` SET login='".$login."', password='".$password."', name='".$client_name."', user_hash='".$new_hash."'");
          /*создаю новую основную таблицу*/
        mysqli_query($conn,"CREATE TABLE `data_".$login."` (`id` INT(11) NOT NULL AUTO_INCREMENT , `date` DATE NULL , `source` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `medium` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `campaign` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `content` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `term` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `device` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `clicks` INT(20) NULL , `goals` INT(20) NULL , `cost` REAL NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        /* создаю запись с аккаунтами */
        mysqli_query($conn,"INSERT INTO `accounts` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `goals` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `data_".$login."` SET date='".$date_start."', source='yandex', medium='medium', campaign='campaign', content='content', term='term', clicks='0', goals='0', cost='0'");
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
	}
    /* ручная загрузка */
	if (isset($_POST['upload_data'])){
		include_once(DIR_AUTH.'check.php');
		if ($auth_role !== '1' & $auth_role !== '0' & $auth_login !== null){
            require_once (DIR.'direct.php');}
		$upload_data = get_main_array($_POST['upload_data'], 1);
        $upload_data_2 = get_main_array($_POST['upload_data_2'], 2);
		$login_querry = "data_".$_POST['login_1'];
		$course = $_POST['course_1'];
		$quer_1 = mysqli_query($conn, "SELECT id FROM `access` WHERE login='".$_POST['login_1']."'");
    	if(mysqli_num_rows($quer_1) < 1)
  		{popup(13);} else {
		for ($d=0; $d<count($upload_data); $d++) {
	        $db_date = date_fix($upload_data[$d][0]);
    	    $db_source = $upload_data[$d][1];
        	$db_medium = $upload_data[$d][2];
	        $db_campaign = $upload_data[$d][3];
    	    $db_content = $upload_data[$d][4];
        	$db_term = key_clear2($upload_data[$d][5]);
	        $db_clicks = $upload_data[$d][6];
    	    $db_cost = cost_fix($upload_data[$d][7], $course);
    	    $db_goals = goals_fusion_2($upload_data[$d], $upload_data_2);   	
            mysqli_query($conn, "INSERT INTO `$login_querry` (`date`, `source`, `medium`, `campaign`, `content`, `term`, `cost`, `clicks`, `goals`) VALUES ('$db_date', '$db_source', '$db_medium', '$db_campaign', '$db_content', '$db_term', '$db_cost', '$db_clicks', '$db_goals');");
        	
    		}
    	}
    }
    if (isset($_POST['add_goal']) & $_POST['add_goal'] !== '') {
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $yandex_counter = $auth_counter;
        $yandex_goal = $_POST['add_goal'];
        $quer_2 = mysqli_query($conn, "SELECT id FROM `goals` WHERE ya_goal='".$_POST['add_goal']."'");
        if(mysqli_num_rows($quer_2) > 0){
            popup(6);
        } elseif (goal_check($yandex_goal, $yandex_counter) or $yandex_log == 'DEMO' or $yandex_log == 'admin') {
            mysqli_query($conn, "INSERT INTO `goals` (`yandex`, `ya_goal`) VALUES ('$yandex_log', '$yandex_goal');");
        } else {
            popup(14);
        }
	}
    if (isset($_POST['del_goal'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $yandex_goal = $_POST['del_goal'];
        $quer_3 = mysqli_query($conn, "SELECT id FROM `goals` WHERE ya_goal='".$_POST['del_goal']."'");
        if(mysqli_num_rows($quer_3) < 1){
            popup(7);
        } else {
            while ($id_list = mysqli_fetch_array($quer_3)) { 
            $g_id = $id_list['id'];
            mysqli_query($conn, "DELETE FROM `goals` WHERE id='$g_id'");
            }}
    }
    if (isset($_POST['new_counter'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $new_counter = $_POST['new_counter'];
        $quer_4 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE ya_counter='".$_POST['new_counter']."'");
        if(mysqli_num_rows($quer_4) > 0){
            popup(8);
        } else {
           mysqli_query($conn, "UPDATE `accounts` SET ya_counter='$new_counter' WHERE yandex='$yandex_log'");
        }
    }
    if (isset($_POST['ads_id'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $new_id = $_POST['ads_id'];
        $quer_6 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE google_id='".$_POST['ads_id']."'");
        if(mysqli_num_rows($quer_6) > 0){
            popup(9);
        } else {
           mysqli_query($conn, "UPDATE `accounts` SET google_id='$new_id' WHERE yandex='$yandex_log'");
        }
    }
    /* удаление клиента  */
    if (isset($_POST['log_del'])){
        $del_id = $_POST['log_del'];
        $del_name = $_POST['name_del'];
        if ($del_id == 'admin' or $del_id == 'DEMO'){
            popup(15);
        } else {
            popup4($del_id, $del_name);
        }
    }
    if (isset($_POST['login_del_final'])){
        $del_id_final = $_POST['login_del_final'];
        /* из таблицы  goals */
        $quer_6_1 = mysqli_query($conn, "SELECT id FROM `goals` WHERE yandex='".$del_id_final."'");
        if(mysqli_num_rows($quer_6_1) > 0){
            while ($id_list = mysqli_fetch_array($quer_6_1)) { 
            $g_id = $id_list['id'];
            mysqli_query($conn, "DELETE FROM `goals` WHERE id='".$g_id."'");
            }}
            /* из таблицы  accounts */
        $quer_6_2 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE yandex='".$del_id_final."'");
        if(mysqli_num_rows($quer_6_2) > 0){
            while ($id_list = mysqli_fetch_array($quer_6_2)) { 
            $g_id = $id_list['id'];
            mysqli_query($conn, "DELETE FROM `accounts` WHERE id='".$g_id."'");
            }}
            /* из таблицы  access */
        $quer_6_3 = mysqli_query($conn, "SELECT id FROM `access` WHERE login='".$del_id_final."'");
        if(mysqli_num_rows($quer_6_3) > 0){
            while ($id_list = mysqli_fetch_array($quer_6_3)) { 
            $g_id = $id_list['id'];
            mysqli_query($conn, "DELETE FROM `access` WHERE id='".$g_id."'");
            }} 
        mysqli_query($conn, "DROP TABLE `data_".$del_id_final."`");  
    } 
     /**/
    if (isset($_POST['login_name'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $new_id = $_POST['login_name'];
        if(not_null_str($new_id)){
           mysqli_query($conn, "UPDATE `access` SET name='$new_id' WHERE login='$yandex_log'");
        } else {
            popup(16);
        }
    }
    if (isset($_POST['vk_id'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $new_id = $_POST['vk_id'];
        $quer_7 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE vk='".$_POST['vk_id']."'");
        if(mysqli_num_rows($quer_7) > 0){
            popup(10);
        } else {
           mysqli_query($conn, "UPDATE `accounts` SET vk='$new_id' WHERE yandex='$yandex_log'");
        }
    }
    if (isset($_POST['fb_id'])){
        include_once(DIR_AUTH.'check.php');
        $yandex_log = $auth_login;
        $new_id = $_POST['fb_id'];
        $quer_8 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE fb='".$_POST['fb_id']."'");
        if(mysqli_num_rows($quer_8) > 0){
            popup(10);
        } else {
           mysqli_query($conn, "UPDATE `accounts` SET fb='$new_id' WHERE yandex='$yandex_log'");
        }
    }

    /*корректировка*/
    if (isset($_POST['login_corr'])){
        $login_corr = $_POST['login_corr'];
        $source_corr = $_POST['source_corr'];
        $sum_corr = $_POST['sum_corr'];
        $date_corr1 = $_POST['date_corr1'];
        $date_corr2 = $_POST['date_corr2'];
        $data_table = "data_".$login_corr;
        $quer_5 = mysqli_query($conn, "SELECT id FROM `accounts` WHERE yandex='".$login_corr."'");
        if(mysqli_num_rows($quer_5) < 1){
            popup(11);
        } else {
           $quer_6 = mysqli_query($conn, "SELECT id, cost FROM `$data_table` WHERE date>='$date_corr1' AND date<='$date_corr2' AND source='".$source_corr."'");
           if(mysqli_num_rows($quer_6) < 1){
               popup(12);
           } else {
                $sum_arr = 0;
                $corr_arr = array();
                $i=0;
                while ($array_corr = mysqli_fetch_array($quer_6)){
                    $sum_arr = $sum_arr + $array_corr['cost'];
                    $corr_arr[$i]['id'] = $array_corr['id'];
                    $corr_arr[$i]['cost'] = $array_corr['cost'];
                    $i++;
                }
                foreach ($corr_arr as $string) {
                    $new_cost = round(((($string['cost'] * $sum_corr) / $sum_arr) + $string['cost']), 2);
                    mysqli_query($conn, "UPDATE `$data_table` SET cost='$new_cost' WHERE id='".$string['id']."'");
                }
           }
        }
    }
}?><html>
<head> 
  <meta charset="utf-8" content="text/html" />
  <title> Simple Stat </title>
  <link rel="stylesheet" type="text/css" href="/styles.css" />
</head>

<body>
  <? include_once(DIR_INCLUDES.'header.php'); ?>
  <? include_once(DIR_INCLUDES.'menu.php'); ?>
  <? include_once(DIR_INCLUDES.'support.php'); ?>
 <div class="center">
    <h3>Настройки</h3>
    <?if ($auth_role == '1') {
        include_once(DIR_SETTING.'tabs1.php'); 
    } else {
        include_once(DIR_SETTING.'tabs.php'); 
    }   ?>
  </div>
  <? 
  include_once(DIR_INCLUDES.'footer.php');
   ?>
</body>
</html>