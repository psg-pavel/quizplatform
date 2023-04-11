<?
include_once($_SERVER['DOCUMENT_ROOT'].'/library.php');
include(DIR_AUTH.'config.php');
if (isset($_COOKIE['hash']))
{
    $query = mysqli_query($conn, "SELECT * FROM access WHERE user_hash = '".$_COOKIE['hash']."' ");
    $userdata = mysqli_fetch_assoc($query);
    if(($userdata['user_hash'] == $_COOKIE['hash']))
      {
        $tab = mysqli_query($conn, "SELECT * FROM access WHERE id='".$userdata['id']."'");
        while ($result = mysqli_fetch_array($tab)) { 
            $auth_name=$result['name'];
            $auth_login=$result['login'];
            $auth_role=$result['role'];
            }
        $tab1 = mysqli_query($conn, "SELECT * FROM accounts WHERE yandex='".$auth_login."'");
        while ($result1 = mysqli_fetch_array($tab1)) { 
            $auth_google=$result1['google'];
            $auth_google_id=$result1['google_id'];
            $auth_counter=$result1['ya_counter'];
            $auth_vk=$result1['vk'];
            $auth_fb=$result1['fb'];
            }
        $tab2 = mysqli_query($conn, "SELECT * FROM goals WHERE yandex='".$auth_login."'");
        $j=0;
        $auth_goal=array();
        while ($result2 = mysqli_fetch_array($tab2)) { 
            $auth_goal[$j]=$result2['ya_goal'];
            $j++;
            }
        $check_auth = true;
    }else{
        $check_auth = false;
    }
}
else
{
    $check_auth = false;
}?>