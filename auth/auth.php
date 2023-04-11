<?
include_once('config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/config.php');
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
?>
<html>
<head> 
  <meta charset="utf-8" content="text/html" />
  <title> Simple Stat </title>
  <link rel="stylesheet" type="text/css" href="../styles.css" />
</head>
<body>
 <? include_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php'); ?>
  <header><h1>Simple Stat</h1></header>
  <? include_once($_SERVER['DOCUMENT_ROOT'].'/includes/menu.php'); ?>
 <div class="center">
    <form method="POST">
        Логин <input id="time" name="login" type="text" required><br><br>
        Пароль <input id="time" name="password" type="password" required><br><br>
        <input id="time" name="login_submit" type="submit" value="Войти"><br><br>
        <a href="/logout.php">Выход<a><br>
    </form>
 </div>
</div>
<? include_once($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php'); ?>
</body>
</html>