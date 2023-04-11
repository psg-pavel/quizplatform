<?include_once('metrika.php');
if (isset($_POST['login_exit'])){
	unset($_COOKIE['hash']);
}
?>
<div id="head">
<?  include_once('login_form.php'); ?>
  <a id="a1">
  <a margin-right="20"><? include_once($_SERVER['DOCUMENT_ROOT'].'/auth/check.php'); if ($check_auth){echo "Клиент: ".$auth_name;}else{echo " Вы не авторизованы! ";} ?></a>
  <a id="a2" href='javascript:login_PopUpShow()'><?if ($check_auth){echo "Сменить";}else{echo "Войти";}?></a>
  </a>
</div>