<?
  include_once(__DIR__.'/library.php');
  include_once(DIR_INCLUDES.'functions.php');
  include_once(DIR_AUTH.'config.php');
    if($_POST) {
        /* добавляем менеджера */
    if (isset($_POST['name_add'])){
    $err = [];
    if(strlen($_POST['log_add']) < 3 or strlen($_POST['log_add']) > 30)
    {$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";}
    
    if($_POST['log_add'] == 'логин' or $_POST['name_add'] == 'имя' or $_POST['pass_add'] == 'пароль')
    {$err[] = "Введите корректные данные";}

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($conn, "SELECT id FROM `access` WHERE login='".mysqli_real_escape_string($conn, $_POST['log_add'])."'");
    if(mysqli_num_rows($query) > 0)
    {$err[] = "Пользователь с таким логином уже существует в базе данных";}

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $login = no_dots_login($_POST['log_add']);
        $client_name = $_POST['name_add'];
        $new_hash = generateCode(10);
        $password = $_POST['pass_add'];
          /*добавляю строку в access*/
        mysqli_query($conn,"INSERT INTO `access` SET login='".$login."', password='".$password."', role='1', name='".$client_name."', user_hash='".$new_hash."'");
          /*создаю новую основную таблицу*/
        mysqli_query($conn,"CREATE TABLE `u0830623_simple_stat`.`data_".$login."` (`id` INT(11) NOT NULL AUTO_INCREMENT , `date` DATE NULL , `source` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `medium` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `campaign` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `content` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `term` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `device` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `clicks` INT(20) NULL , `goals` INT(20) NULL , `cost` REAL NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        /* создаю запись с аккаунтами */
        mysqli_query($conn,"INSERT INTO `accounts` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `goals` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `data_".$login."` SET source='yandex', medium='medium', campaign='campaign', content='content', term='term', clicks='0', goals='0', cost='0'");
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
        /* добавляем админа */
    if (isset($_POST['adm_name_add'])){
    $err = [];
    if(strlen($_POST['adm_log_add']) < 3 or strlen($_POST['adm_log_add']) > 30)
    {$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";}
    
    if($_POST['adm_log_add'] == 'логин' or $_POST['adm_name_add'] == 'имя' or $_POST['adm_pass_add'] == 'пароль')
    {$err[] = "Введите корректные данные";}

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($conn, "SELECT id FROM `access` WHERE login='".mysqli_real_escape_string($conn, $_POST['adm_log_add'])."'");
    if(mysqli_num_rows($query) > 0)
    {$err[] = "Пользователь с таким логином уже существует в базе данных";}

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $login = no_dots_login($_POST['adm_log_add']);
        $client_name = $_POST['adm_name_add'];
        $new_hash = generateCode(10);
        $password = $_POST['adm_pass_add'];
          /*добавляю строку в access*/
        mysqli_query($conn,"INSERT INTO `access` SET login='".$login."', password='".$password."', role='2', name='".$client_name."', user_hash='".$new_hash."'");
          /*создаю новую основную таблицу*/
        mysqli_query($conn,"CREATE TABLE `u0830623_simple_stat`.`data_".$login."` (`id` INT(11) NOT NULL AUTO_INCREMENT , `date` DATE NULL , `source` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `medium` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `campaign` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `content` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `term` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `device` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `clicks` INT(20) NULL , `goals` INT(20) NULL , `cost` REAL NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        /* создаю запись с аккаунтами */
        mysqli_query($conn,"INSERT INTO `accounts` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `goals` SET yandex='".$login."'");
        mysqli_query($conn,"INSERT INTO `data_".$login."` SET source='yandex', medium='medium', campaign='campaign', content='content', term='term', clicks='0', goals='0', cost='0'");
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
    /* удаление менеджера  */
    if (isset($_POST['man_del'])){
        $del_id = $_POST['man_del'];
        $del_name = $_POST['man_name_del'];
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
/* удаление админа  */
    if (isset($_POST['adm_del'])){
        $del_id = $_POST['adm_del'];
        $del_name = $_POST['adm_name_del'];
        if ($del_id == 'adm' or $del_id == 'DEMO'){
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
     /*сменить имя*/
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
      <?if ($auth_role == '2') { ?>
 <div class="center">
    <h3>Настройки</h3>
<div class="tabs">
    <input type="radio" name="inset" value="" id="tab_1" checked>
    <label for="tab_1">Общие</label>

    <input type="radio" name="inset" value="" id="tab_2">
    <label for="tab_2">Список менеджеров</label>

    <input type="radio" name="inset" value="" id="tab_3">
    <label for="tab_3">Список админов</label>

    <input type="radio" name="inset" value="" id="tab_4">
    <label for="tab_4">Корректировка данных</label>

    <div class='table_list' id="txt_1">
        <h3> <?php 
        print_row(3, "Ваш логин:", not_null_echo($auth_login), "login_name", $auth_name);?>
        </h3>
    </div>
    <div class='table_list' id="txt_2">
    <?php include_once(DIR_INCLUDES.'manager_list.php'); ?>
    </div>

    <div class='table_list' id="txt_3">
    <?php include_once(DIR_INCLUDES.'admin_list.php'); ?>
    </div>

    <div class='table_list' id="txt_4">
    <?php include_once(DIR_INCLUDES.'correct.php');  ?>
    </div>
  </div>
 
  <? }else{ ?> 
    <div style='margin:auto;'>Вы не администратор системы! ((</div>  
</div>
    <?}?> 
  
  <?  include_once(DIR_INCLUDES.'footer.php');  ?>

</body>
</html>