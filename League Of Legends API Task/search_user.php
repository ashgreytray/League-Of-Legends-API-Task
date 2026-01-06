<?php
header('Content-Type: application/json');

// === CONFIGURATION ===
$API_KEY = "remove api key";

// === INPUT VALIDATION ===
$riot_id = $_GET['riot_id'] ?? '';
$platform = $_GET['platform'] ?? 'oc1'; // for summoner/ranked
$routing = $_GET['routing'] ?? 'asia';   // for match data

if (!str_contains($riot_id, '#')) {
    echo json_encode(['error' => 'Invalid Riot ID format. Use Username#Tag']);
    exit;
}

[$gameName, $tagLine] = explode('#', $riot_id);

// === STEP 1: Faked Riot ID -> PUUID ===
// Instead of calling Riot API, use hardcoded PUUID for "sup that doms u #2222"
$puuid = "a2Lbzn3Ce1RVe8LgPxHvEsbB81RkrZeFDsgt40pYDQNGjuf_MrDOvGytadXfRm3nfmGgnGFEpyCymw";
$account_data = [
    'gameName' => $gameName,
    'tagLine' => $tagLine,
    'puuid' => $puuid
];
$summoner_url = "https://$platform.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid";
$summoner_data = riot_api_get($summoner_url, $API_KEY);
$summoner_id = $summoner_data['id'] ?? null;
if (!$summoner_id) {
    echo json_encode(['error' => 'Summoner data not found']);
    exit;
}
$rank_url = "https://$platform.api.riotgames.com/lol/league/v4/entries/by-summoner/$summoner_id";
$rank_data = riot_api_get($rank_url, $API_KEY);
$match_ids_url = "https://$routing.api.riotgames.com/lol/match/v5/matches/by-puuid/$puuid/ids?start=0&count=5";
$match_ids = riot_api_get($match_ids_url, $API_KEY);
$matches = [];
if (is_array($match_ids)) {
    foreach ($match_ids as $match_id) {
        $match_url = "https://$routing.api.riotgames.com/lol/match/v5/matches/$match_id";
        $match_data = riot_api_get($match_url, $API_KEY);
        if ($match_data) $matches[] = $match_data;
    }
}
echo json_encode([
    'riot_id' => $riot_id,
    'account' => $account_data,
    'puuid' => $puuid,
    'summoner' => $summoner_data,
    'ranked' => $rank_data,
    'matches' => $matches
], JSON_PRETTY_PRINT);


// === SHARED FUNCTION ===
function riot_api_get($url, $api_key) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["X-Riot-Token: $api_key"]
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

?>

