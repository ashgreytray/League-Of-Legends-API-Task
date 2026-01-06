<?php
// === Backend: get_match_data.php ===

$matchId = $_GET['match_id'] ?? '';
$apiKey = "remove api key";

if (!$matchId) {
    http_response_code(400);
    echo json_encode(["error" => "Match ID required"]);
    exit;
}

$url = "https://oc1.api.riotgames.com/lol/match/v5/matches/$matchId";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Riot-Token: $apiKey"]);
$response = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
echo $response;
?>

