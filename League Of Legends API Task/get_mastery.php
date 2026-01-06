===========================
File: get_mastery.php
===========================
<?php
header('Content-Type: application/json');

$API_KEY = 'RGAPI-d2c03524-6371-4940-8cf4-d305391cecd3';
$puuid = $_GET['puuid'] ?? '';
$platform = $_GET['platform'] ?? 'oc1';

if (!$puuid || !$platform) {
    echo json_encode(['error' => 'Missing puuid or platform']);
    exit;
}

$url = "https://$platform.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-puuid/$puuid";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["X-Riot-Token: $API_KEY"]
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo json_encode(array_slice($data, 0, 3));

