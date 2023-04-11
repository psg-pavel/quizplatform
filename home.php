<?php 
include_once(__DIR__.'/library.php');
include_once('includes/functions.php');
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
?>
<html>
<head> 
  <meta charset="utf-8" content="text/html" />
  <title> Simple Stat </title>
  <link rel="stylesheet" type="text/css" href='styles.css' />
</head>
<body>
  <? 
  include_once('includes/header.php'); 
  include_once('includes/menu.php');?>
  <div class="center">
  	<?
	if ($auth_role =='1' or $auth_role =='2') {
		  echo "<h3>Подключенные пользователи:</h3>
            <h4>Данные для предоставления клиенту доступны по клику</h4>";
          include_once('includes/login_list.php');}
	elseif (!$auth_login) {
	 	  echo "<h3>Вы не авторизованы</h3>";
	 	  echo "<h4>Для изучения возможностей системы, вы можете</h4>";
          echo "<div >
            <form method='POST' >
            <button name='login_submit' style='float: left;'>войти в DEMO кабинет</button>
			<input name='login' type='hidden' value='DEMO'>
			<input name='password' type='hidden' value='DEMO'>
            </form></div>";
			}
  	?>
  </div>
 <? include_once('includes/footer.php'); ?>
</body>
</html>