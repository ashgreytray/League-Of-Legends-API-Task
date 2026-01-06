<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$apiKey = 'remove api key';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['riotId']) || !isset($data['region'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$riotId = $data['riotId'];
$region = $data['region'];

if (!str_contains($riotId, '#')) {
    echo json_encode(['error' => 'Invalid Riot ID format']);
    exit;
}

[$gameName, $tagLine] = explode('#', $riotId, 2);

$url = "https://$region.api.riotgames.com/riot/account/v1/accounts/by-riot-id/" . rawurlencode($gameName) . "/" . rawurlencode($tagLine);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["X-Riot-Token: $apiKey"]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['error' => "Riot API error (HTTP $httpCode)"]);
    exit;
}

$result = json_decode($response, true);
echo json_encode([
    'puuid' => $result['puuid'],
    'gameName' => $result['gameName'],
    'tagLine' => $result['tagLine']
]);

