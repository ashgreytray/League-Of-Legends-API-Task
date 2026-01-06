
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RIOT API</title>
  <style>
    body { font-family: sans-serif; background: #111; color: #fff; padding: 20px; }
    select, input, button { padding: 8px; margin: 5px; }
    pre { background: #222; padding: 15px; overflow-x: auto; }
    #exportBtn { background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; }
  </style>
</head>
<body>
  <h1>Riot ID Lookup </h1>
  <input type="text" id="riotId" placeholder="e.g. Asphodel#phoon">
  <select id="region">
    <option value="oc1|sea">Oceania</option>
    <option value="na1|americas">North America</option>
    <option value="euw1|europe">Europe West</option>
    <option value="eun1|europe">Europe Nordic & East</option>
    <option value="kr|asia">Korea</option>
    <option value="jp1|asia">Japan</option>
    <option value="br1|americas">Brazil</option>
    <option value="las|americas">LAS</option>
    <option value="lan|americas">LAN</option>
    <option value="ru|europe">Russia</option>
    <option value="tr1|europe">Türkiye</option>
    <option value="sg2|sea">Southeast Asia</option>
    <option value="tw2|sea">Taiwan</option>
    <option value="vn2|sea">Vietnam</option>
  </select>
  <button onclick="search()">Search</button>
  <button id="exportBtn">Export JSON</button>

  <pre id="output">Results will appear here...</pre>

  <script>
    let latestData = null;

    async function search() {
      const riotId = document.getElementById('riotId').value.trim();
      const regionVal = document.getElementById('region').value;
      const [platform, routing] = regionVal.split('|');

      const response = await fetch(`search_user.php?riot_id=${encodeURIComponent(riotId)}&platform=${platform}&routing=${routing}`);
      const data = await response.json();
      latestData = data; // Store for export

      document.getElementById('output').textContent = JSON.stringify(data, null, 2);
    }

    document.getElementById('exportBtn').addEventListener('click', () => {
      if (!latestData) {
        alert("No data to export. Perform a search first.");
        return;
      }
      const blob = new Blob([JSON.stringify(latestData, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);

      const a = document.createElement('a');
      a.href = url;
      a.download = 'riot_user_data.json';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    });
  </script>
</body>
</html>
