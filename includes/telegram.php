<?php
function sendToTelegram($message) {
    $token = '7214881203:AAEv4f3QzBznV0KMUcXoWUq-arfksyWlfQg';
    $chat_id = '6084754935';
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = array(
        'chat_id' => $chat_id,
        'text' => $message
    );

    $options = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => "Content-Type:application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data),
        )
    );

    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
