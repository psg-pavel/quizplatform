  <?include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');;?>
  <header>
    <img src="<?echo DIR_DOMAIN;?>LOGO4.svg" style='float: left; width: 99px;'><h1 style="margin:auto;">Simple Stat</h1></header>
  <div class="left">
<?php if ($auth_role) {?>
  <a href="<?echo DIR_DOMAIN;?>home.php"><img src="<?echo DIR_IMAGES;?>home.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>home.php">Клиенты</a></a><br><br>
<?}?>
<?php if ($auth_role !== '2' & $auth_role !== '1') {?>

  <a href="<?echo DIR_DOMAIN;?>quiz.php"><img src="<?echo DIR_IMAGES;?>quiz.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>quiz.php">Квиз</a></a><br><br>

  <?}?>
<?php if ($auth_role !== '2') {?>
  <a href="<?echo DIR_DOMAIN;?>setting/index.php"><img src="<?echo DIR_IMAGES;?>sett.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>setting/index.php">Настройки</a></a><br><br>

  <a href="<?echo DIR_DOMAIN;?>book.php"><img src="<?echo DIR_IMAGES;?>faq.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>book.php">Справка</a></a><br><br>
<?}?>
<?php if ($auth_role == '2') {?>
  <a href="<?echo DIR_DOMAIN;?>admin.php"><img src="<?echo DIR_IMAGES;?>adm.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>admin.php">Админ панель</a></a><br><br>

  <a href="<?echo DIR_DOMAIN;?>book2.php"><img src="<?echo DIR_IMAGES;?>faq.png" style='float: left;'>
  <a style='padding: 5px;' id="link" href="<?echo DIR_DOMAIN;?>book2.php">Справка</a></a><br><br>
<?}?>
  </div>