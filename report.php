<?php 
include_once('includes/functions.php');
require_once('auth/check.php');
$yesterday = date('Y-m-d', strtotime('yesterday'));
$login_querry = "data_".$auth_login;
$report_name = $auth_login."_".time();
/* если дата в базе не актуальна... */
if (last_date($conn, $login_querry) !== $yesterday) {
    $last_date1 = date('Y-m-d', strtotime(last_date($conn, $login_querry). ' + 1 days'));   
    /*------------директ---------------*/
    ob_implicit_flush();
    $url = 'https://googleads.googleapis.com/v9/customers/3147586813/googleAds:search';
    $token = 'AgAAAAA6mY5RAAYmHzeo-unIpEI3imOnq9MI-yo';
    $clientLogin = $auth_login;
    $params = "SELECT 
                campaign.name, campaign.status, segments.device,
                metrics.impressions, metrics.clicks, metrics.ctr,
                metrics.average_cpc, metrics.cost_micros
                FROM campaign
                WHERE segments.date DURING LAST_30_DAYS";
    $body = json_encode($params);
    $headers = array(
    //"Authorization: Bearer 1//0cTwoK0Gh5lf3CgYIARAAGAwSNwF-L9Iro6G2BEOIlj5u2in5FCK3a9TyW4di0q4HIFrfeU7F89wcuiynhPQB7FUU8VBmeEL8zuo",
    "Authorization: Bearer 87cd235e1fd62616eaa93f8e2b68386ccbc1346e",
    "developer-token: c5i4GCUsGRT3spZrq0fcTA",
    "User-Agent: curl",
    "Content-Type: application/json",
    "Accept: application/json"
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



}
?>