<?php
header('Content-Type: application/json');

$api_key = 'RGAPI-d2c03524-6371-4940-8cf4-d305391cecd3';

if (!isset($_GET['riot_id']) || !isset($_GET['region'])) {
    echo json_encode(['error' => 'Missing riot_id or region parameter']);
    exit;
}

$riot_id = $_GET['riot_id'];
$region = $_GET['region'];

if ($region !== 'oc1') {
    echo json_encode(['error' => 'Only Oceania region is supported in this implementation']);
    exit;
}

$gameName = '';
$tagLine = '';
$parts = explode('#', $riot_id);
if (count($parts) != 2) {
    echo json_encode(['error' => 'Invalid Riot ID format, expected Username#TAG']);
    exit;
}

$gameName = urlencode($parts[0]);
$tagLine = urlencode($parts[1]);

$account_api_url = "https://americas.api.riotgames.com/riot/account/v1/accounts/by-riot-id/$gameName/$tagLine";

$account_response = file_get_contents($account_api_url . "?api_key=$api_key");
if ($account_response === false) {
    echo json_encode(['error' => 'Failed to fetch account data', 'debug' => ['url' => $account_api_url]]);
    exit;
}

$account_data = json_decode($account_response, true);
if (!isset($account_data['puuid'])) {
    echo json_encode(['error' => 'PUUID not found or invalid Riot ID', 'debug' => ['url' => $account_api_url, 'response' => $account_data]]);
    exit;
}

$puuid = $account_data['puuid'];

$response = [
  'puuid' => $puuid,
  'debug' => [
    'account_api_url' => $account_api_url
  ]
];

echo json_encode($response);
