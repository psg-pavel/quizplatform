    <form method='POST'>
      <? echo "<input id='time' name='login_1' type='hidden' value='".$auth_login."'><br>";?>
      Курс валют: <input id='time' name='course_1' type='text' required value='1'><br>
      <h4>Из кабинета</h4>
      Дата || источник || тип трафика || кампания || группа || ключ || клики || стоимость(БЕЗ НДС) || номер группы <br><br>
      <textarea id='time' type='text' name='upload_data'></textarea><br><br>
      <h4>Из метрики</h4>
      Дата || метка from || лиды <br><br>
      <textarea id='time' type='text' name='upload_data_2'></textarea><br><br>
      <input id='time' name='submit' type='submit' value='Загрузить'><br><br>
    </form>