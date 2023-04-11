<?
include('auth/config.php');
$tab = mysqli_query($conn, "SELECT * FROM access");
        $id = 10;
        while ($result = mysqli_fetch_array($tab)) { 
            $login_name=$result['name'];
            $login_login=$result['login'];
            $login_pass=$result['password'];
            $login_role=$result['role'];
            if ($login_role !== '1' & $login_role !== '2'){
            echo "<div>
            <div >
            <form method='POST' >
            <button name='login_submit' style='float: left; width: 50px;'>войти</button>
			<input name='login' type='hidden' value='$login_login'>
			<input name='password' type='hidden' value='$login_pass'>
            </form></div>
            <a href='javascript:ShowLogin(".$id.")'>
            <div style='float: left; width: 300px; padding-left: 10px;'>".$login_name."</div>
            </a>
            <div ><form method='POST' >
            <button name='submit' type='submit' style='float: left; width: 65px;'>удалить</button>
            <input name='log_del' type='hidden' value='".$login_login."'>
            <input name='name_del' type='hidden' value='".$login_name."'>
            </form></div><br>";
            popupShowLogin($id, $login_name, $login_login, $login_pass);
            echo "</div>" ;
            }
            $id++;
            }
echo "<br>";
?>