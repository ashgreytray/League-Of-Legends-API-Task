<?php
function riot_api_get($url, $api_key, &$status_code = null) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["X-Riot-Token: $api_key"],
        CURLOPT_HEADER => true
    ]);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    return json_decode($body, true);
}
?>
