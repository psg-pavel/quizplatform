<div class="tabs">
    <input type="radio" name="inset" value="" id="tab_1" checked>
    <label for="tab_1">Общие</label>

    <input type="radio" name="inset" value="" id="tab_2">
    <label for="tab_2">Подключить нового клиента</label>

    <div class='table_list' id="txt_1">
        <h3> <?php 
        print_row(3, "Ваш логин:", not_null_echo($auth_login), "login_name", $auth_name);?>
        </h3>
    </div>
    <div class='table_list' id="txt_2">
    <?php
    include_once(DIR_AUTH.'check.php');
    include_once(DIR_INCLUDES.'login.php');
    ?>
    </div>

  </div>