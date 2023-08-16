<?php

$clientId = '2e4388f5a7bdaedd1672d69d1c148a54'; // тут свой клиент
if (empty($_GET['access_token'])) {
    $redirectUrl = 'https://test.schildt.ru/login.php';

    setcookie("auth", 'типо ид сессии', time()+3600); 
    setcookie("nick", 'NULL',           time()+3600);

    $url = 'https://open.trovo.live/page/login.html?client_id=' . $clientId . '&response_type=token&scope=user_details_self&redirect_uri=' . $redirectUrl . '&state=statedata';

    header('Location: '.$url);
} else {
    // Валидация, возврат профиля
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://open-api.trovo.live/openplatform/validate');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Client-ID: ' . $clientId,
        'Authorization: OAuth ' . $_GET['access_token'],
    ]);
    $res = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($res);

    $profile_id = $res->uid; // Профиль, он же id для чата
    $profile_nickname = $res->nick_name; // кастомный ник

    $sess = $_COOKIE["auth"];
    // мб проверки какие... запись в бд... отсюда уже стартует авторизация самих рулеток
    setcookie("nick", $profile_nickname);
    header('Location: '.'https://test.schildt.ru');
}
