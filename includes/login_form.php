    <div class='attention-popup' id='login_popup' style='display: none;'>
       <div class='login-popup-content'>
        <form method="POST">
        <br><br>
        Логин <input class="time" name="login" type="text" required><br><br>
        Пароль <input style="margin-right: 8px;" class="time" name="password" type="password" required><br><br>
        <input class="time" name="login_submit" type="submit" value="Войти"><br><br><br><br><br>
        </form>
        <!--
        <form method="POST">
            <input name="login_exit" type='hidden' value=''><br>
            <input id="time" name="login_ex" type="submit" value="Выход"><br>
        </form>  -->
        <br><a href='javascript:login_PopUpHide()'>ОТМЕНА</a><br>
        <? echo $_COOKIE['login_submit'];?>
        
      </div>
    </div> 