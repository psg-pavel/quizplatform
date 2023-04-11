<?php 
include_once(DIR_INCLUDES.'functions.php');
require_once(DIR_AUTH.'check.php');
$yesterday = date('Y-m-d', strtotime('yesterday'));
$login_querry = "data_".$auth_login;
$report_name = $auth_login."_".time();
/* если дата в базе не актуальна... */
if (last_date($conn, $login_querry) !== $yesterday) {
    $last_date1 = date('Y-m-d', strtotime(last_date($conn, $login_querry). ' + 1 days'));   
    /*------------директ---------------*/
    ob_implicit_flush();
    $url = 'https://api.direct.yandex.com/json/v5/reports';
    $token = 'AgAAAAA6mY5RAAYmHzeo-unIpEI3imOnq9MI-yo';
    $clientLogin = $auth_login;
    $params = [
    "params" => [
        "SelectionCriteria" => [
            "DateFrom" => "$last_date1",
            "DateTo" => "$yesterday",
            "Filter" => [
                [
                "Field" => "Clicks",
                "Operator" => "GREATER_THAN",
                "Values" => ["0"]
                ]
                ]
            ],
            "FieldNames" => ["Date", "AdNetworkType", "CampaignName", "AdGroupName", "Criterion", "Clicks", "Cost", "AdGroupId", "CriterionType"],
            "ReportName" => "$report_name",
            "ReportType" => "CUSTOM_REPORT",
            "DateRangeType" => "CUSTOM_DATE",
            "Format" => "TSV",
            "IncludeVAT" => "NO",
            "IncludeDiscount" => "NO"
            ]
        ];
    $body = json_encode($params);
    $headers = array(
    "Authorization: Bearer $token",
    "Client-Login: $clientLogin",
    "Accept-Language: ru", 
    "processingMode: auto",
    "skipReportHeader: true",
    "skipColumnHeader: true",
    "returnMoneyInMicros: false",
    "skipReportSummary: true" 
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // --- Запуск цикла для выполнения запросов ---
    while (true) {
        $result = curl_exec($curl);
        if (!$result) {
            echo ('Ошибка cURL: '.curl_errno($curl).' - '.curl_error($curl));
            break;
        } else {
            $responseHeadersSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $responseHeaders = substr($result, 0, $responseHeadersSize);
            $responseBody = substr($result, $responseHeadersSize);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $requestId = preg_match('/RequestId: (\d+)/', $responseHeaders, $arr) ? $arr[1] : false;
            $retryIn = preg_match('/retryIn: (\d+)/', $responseHeaders, $arr) ? $arr[1] : 60;
            if ($httpCode == 400) {
                echo "Параметры запроса указаны неверно или достигнут лимит отчетов в очереди<br>";
                echo "RequestId: {$requestId}<br>";
                echo "JSON-код запроса:<br>{$body}<br>";
                echo "JSON-код ответа сервера:<br>{$responseBody}<br>";
                break;
            } elseif ($httpCode == 200) {
                /*echo "Отчет создан успешно<br>";
                echo "RequestId: {$requestId}<br>";
                echo '<pre>';*/
                break;
            } elseif ($httpCode == 201) {
                /*echo "Отчет успешно поставлен в очередь в режиме офлайн<br>";
                echo "Повторная отправка запроса через {$retryIn} секунд<br>";
                echo "RequestId: {$requestId}<br>";*/
                sleep($retryIn);
            } elseif ($httpCode == 202) {
                /*echo "Отчет формируется в режиме offline.<br>";
                echo "Повторная отправка запроса через {$retryIn} секунд<br>";
                echo "RequestId: {$requestId}<br>";*/
                sleep($retryIn);
            } elseif ($httpCode == 500) {
                echo "При формировании отчета произошла ошибка. Пожалуйста, попробуйте повторить запрос позднее<br>";
                echo "RequestId: {$requestId}<br>";
                echo "JSON-код ответа сервера:<br>{$responseBody}<br>";
                break;
            } elseif ($httpCode == 502) {
                echo "Время формирования отчета превысило серверное ограничение.<br>";
                echo "Пожалуйста, попробуйте изменить параметры запроса - уменьшить период и количество запрашиваемых данных.<br>";
                echo "RequestId: {$requestId}<br>";
                break;
            } else {
                echo "Произошла непредвиденная ошибка.<br>";
                echo "RequestId: {$requestId}<br>";
                echo "JSON-код запроса:<br>{$body}<br>";
                echo "JSON-код ответа сервера:<br>{$responseBody}<br>";
                break;
            }
        }
    }
    curl_close($curl);
    $direct = array();
    $strings = explode("\n", $responseBody);
    for ($s=0; $s<count($strings)-1; $s++) {
        $direct[$s] = explode("\t", $strings[$s]);
    }


    /*------------метрика---------------*/
    $new_goals = array();
    if ($auth_counter !== NULL) {
        $sURL = 'https://api-metrika.yandex.net/stat/v1/data?ids='.$auth_counter.'&metrics=ym:s:visits'.goals_metrics($auth_goal).'&date1='.$last_date1.'&date2=yesterday&dimensions=ym:s:date,ym:s:from&attribution=last_yandex_direct_click&limit=100000'; // URL-адрес GET
        $aHTTP = array(
            'http' => // Обертка, которая будет использоваться
                array(
                    'method' => 'GET', // Метод запроса
                    // Ниже задаются заголовки запроса
                    'header' => 'Authorization: OAuth AgAAAAA5S1H3AAY2Gcc-ALgaekTjsX3lVKB3GXo'
                )
        );
        $context = stream_context_create($aHTTP);
        $data = json_decode(file_get_contents($sURL, false, $context));
        $old_data = $data -> data;
        $metrika = json_decode(json_encode($old_data), true);
        $new_goals = goals_clear($metrika);
        unset($sURL, $aHTTP, $context, $data, $old_data, $metrika);
    }
    /* раставляем лиды по массиву директ */
     for ($d=0; $d<count($new_goals); $d++) {
        $g_fuse = goals_fusion($new_goals[$d], $direct);
        if ($g_fuse[0]){
            array_push($direct[$g_fuse[1]], $new_goals[$d]['metrics'][0]);
            unset($new_goals[$d]);
        }
    }  
    /* добавляем неразобранные лиды вручную */
    foreach ($new_goals as $goal_str) {
        $new_str = array();
        $g_fuse = goals_fusion3($goal_str, $direct);
        if ($g_fuse[0]){
            $new_str[0] = $goal_str['dimensions'][0]['name'];
            $new_str[1] = $g_fuse[1];
            $new_str[2] = $g_fuse[2];
            $new_str[3] = $g_fuse[3];
            $new_str[4] = " ";
            $new_str[5] = 0; $new_str[6] = 0; $new_str[7] = 0; $new_str[8] = 0;
            $new_str[9] = $goal_str['metrics'][0];
            array_push($direct, $new_str);
        }
    }
       
    /*--- отправка в БД  --*/
    for ($d=0; $d<count($direct); $d++) {
        $db_date = $direct[$d][0];
        $db_source = 'yandex';
        $db_medium = $direct[$d][1];
        $db_campaign = $direct[$d][2];
        $db_content = $direct[$d][3];
        $db_term = key_clear($direct[$d][4]);
        $db_clicks = $direct[$d][5];
        $db_cost = (round(($direct[$d][6] * 1.2), 2));
        $db_goals = $direct[$d][9];

        mysqli_query($conn, "INSERT INTO `$login_querry` (`date`, `source`, `medium`, `campaign`, `content`, `term`, `cost`, `clicks`, `goals`) VALUES ('$db_date', '$db_source', '$db_medium', '$db_campaign', '$db_content', '$db_term', '$db_cost', '$db_clicks', '$db_goals');");
        /*вывод результата для теста*/
/*
       if ($db_goals > 0) {
        echo $db_date.' '.$direct[$d][7].' '.$db_source.' '.$db_medium.' '.$db_campaign.' '.$db_content.' '.$db_term.' '.$db_cost.' '.$db_clicks.' '.$db_goals.'<br>';}*/
    }

    /* удаляем тестовую строку, если требуется */
    $quer_t = mysqli_query($conn, "SELECT id FROM `$login_querry`");
        if(mysqli_num_rows($quer_t) > 1){
            $quer_test = mysqli_query($conn, "SELECT id FROM `$login_querry` WHERE medium='medium'");
            if(mysqli_num_rows($quer_test) > 0){
                mysqli_query($conn, "DELETE FROM `$login_querry` WHERE medium='medium'");
            }
        }
    unset($direct, $new_goals);
}
?>