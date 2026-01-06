<?php
include 'riot_api_get.php';

$API_KEY = "RGAPI-d2c03524-6371-4940-8cf4-d305391cecd3";
$puuid = $_GET['puuid'] ?? '';
$platform = $_GET['platform'] ?? '';
$routing = $_GET['routing'] ?? '';

if (!$puuid || !$platform || !$routing) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required params']);
    exit;
}

// Get ranked data
$rankUrl = "https://$platform.api.riotgames.com/lol/league/v4/entries/by-summoner/$puuid";
$rankData = riot_api_get($rankUrl, $API_KEY);

// Get matches
$matchIdsUrl = "https://$routing.api.riotgames.com/lol/match/v5/matches/by-puuid/$puuid/ids?start=0&count=5";
$matchIds = riot_api_get($matchIdsUrl, $API_KEY);

$matches = [];
foreach ($matchIds as $matchId) {
    $matchUrl = "https://$routing.api.riotgames.com/lol/match/v5/matches/$matchId";
    $matchData = riot_api_get($matchUrl, $API_KEY);
    $matches[] = $matchData;
}

echo json_encode(['ranked' => $rankData, 'matches' => $matches]);
