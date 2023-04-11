<div class="tabs">
    <input type="radio" name="inset" value="" id="tab_1" checked>
    <label for="tab_1">Общие</label>

    <input type="radio" name="inset" value="" id="tab_2">
    <label for="tab_2">Рекламные кабинеты</label>

    <input type="radio" name="inset" value="" id="tab_3">
    <label for="tab_3">Яндекс Метрика</label>

    <input type="radio" name="inset" value="" id="tab_4">
    <label for="tab_4">Загрузка данных</label>

    <div class='table_list' id="txt_1">
        <h3> <?php 
        print_row(3, "Ваш логин:", not_null_echo($auth_login), "login_name", $auth_name);?>
        </h3>
    </div>
    <div class='table_list' id="txt_2">
    <? 
    print_row(1, "Логин Яндекс Директ", not_null_echo($auth_login), "yan_id", 1);
    print_row(1, "id Google Ads", not_null_echo($auth_google_id), "ads_id", 3);
    print_row(1, "аккаунт VK.com", not_null_echo($auth_vk), "vk_id", 4);
    //print_row(1, "аккаунт FaceBook", not_null_echo($auth_fb), "fb_id", 5);     
     ?>
    </div>
    <div class='table_list' id="txt_3">
    <? 
    print_row(1, "Cчетчик Я.Метрики", not_null_echo($auth_counter), "new_counter", 1);
    print_row(2, "Номера целей", print_goal_list($auth_goal), "goal", 2);    
     ?>
    </div>

    <div class='table_list' id="txt_4">
    <?php
    if ($check_auth){
    include_once(DIR_INCLUDES.'google_date.php');
    include_once(DIR_INCLUDES.'upload.php');}else{echo "Необходимо авторизоваться ((";}
    ?>
    </div>
  </div>