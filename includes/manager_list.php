<?php
include(DIR_AUTH.'config.php');
$tab = mysqli_query($conn, "SELECT * FROM access WHERE `role`='1'");?>
<table class='no-border' cellspacing="0">
<thead>
      <tr>
            <td> </td>
            <td>Имя</td>
            <td>Логин</td>
            <td>Пароль</td>
            <td> </td>
      </tr>
</thead>
<tbody cellspacing="0">
<?while ($result = mysqli_fetch_array($tab)) { 
      $login_name=$result['name'];
      $login_login=$result['login'];
      $login_pass=$result['password'];
      $login_role=$result['role'];
      echo "<tr style='height:25px !important;'>
            <td style='text-align:center; padding-top:10px;'>
            <div>
                  <form method='POST' >
                        <button name='login_submit'>войти</button>
                        <input name='login' type='hidden' value='".$login_login."'>
                        <input name='password' type='hidden' value='".$login_pass."'>
                  </form>
            </div>
            </td>
            <td>
            <div>".$login_name."</div>
            </td>
            <td>
            <div>".$login_login."</div>
            </td>
            <td>
            <div>"; if ($login_login !== 'admin') {echo $login_pass;}else{echo '****';} echo "</div>
            </td>
            <td style='text-align:center; padding-top:10px;'>
            <div >
                  <form method='POST'>
                        <button name='submit' type='submit' style='width: 65px;'>удалить</button>
                        <input name='man_del' type='hidden' value='".$login_login."'>
                        <input name='man_name_del' type='hidden' value='".$login_name."'>
                  </form>
            </div>
            </td></tr>";}?>

</tbody>
</table>
            <p>Добавить менеджера</p>
            <form method='POST'>
                  <input id='times' name='log_add' type='text' value='логин'>
                  <input id='times' name='name_add' type='text' value='имя'>
                  <input id='times' name='pass_add' type='text' value='пароль'>
                  <button id='times' name='submit' type='submit'>Добавить</button>
            </form><br> 