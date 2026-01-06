document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const summonerName = document.getElementById('summonerName').value;
    const region = document.getElementById('region').value;
    
    fetch('api/fetch_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ summonerName, region })
    })
    .then(response => response.json())
    .then(data => {
        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = '';
        
        if (data.error) {
            resultsDiv.textContent = data.error;
            return;
        }
        
        // Display ranked stats
        const ranked = data.ranked;
        const kda = ((ranked.kills + ranked.assists) / (ranked.deaths || 1)).toFixed(2);
        const winrate = ((ranked.wins / (ranked.wins + ranked.losses)) * 100).toFixed(2);
        
        const kdaClass = kda >= 4 ? 'good' : kda >= 2 ? 'average' : 'bad';
        const winrateClass = winrate >= 60 ? 'good' : winrate >= 50 ? 'average' : 'bad';
        
        const rankedDiv = document.createElement('div');
        rankedDiv.innerHTML = `
            <h2>Ranked Stats</h2>
            <p>Tier: ${ranked.tier} ${ranked.rank}</p>
            <p class="kda ${kdaClass}">KDA: ${kda}</p>
            <p class="winrate ${winrateClass}">Win Rate: ${winrate}%</p>
        `;
        resultsDiv.appendChild(rankedDiv);
        
        // Display match history
        const matches = data.matches;
        const matchesDiv = document.createElement('div');
        matchesDiv.innerHTML = '<h2>Match History</h2>';
        
        matches.forEach(match => {
            const matchDiv = document.createElement('div');
            matchDiv.classList.add('match');
            
            const matchKDA = ((match.kills + match.assists) / (match.deaths || 1)).toFixed(2);
            const matchKDAClass = matchKDA >= 4 ? 'good' : matchKDA >= 2 ? 'average' : 'bad';
            
            matchDiv.innerHTML = `
                <p>Champion: ${match.champion}</p>
                <p class="kda ${matchKDAClass}">KDA: ${matchKDA}</p>
                <button class="toggleDetails">Show Details</button>
                <div class="details" style="display:none;">
                    <div class="team">
                        <h4>Team 1</h4>
                        <ul>
                            ${match.team1.map(player => `<li>${player}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="team">
                        <h4>Team 2</h4>
                        <ul>
                            ${match.team2.map(player => `<li>${player}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
            
            matchesDiv.appendChild(matchDiv);
        });
        
        resultsDiv.appendChild(matchesDiv);
        
        // Add toggle functionality
        document.querySelectorAll('.toggleDetails').forEach(button => {
            button.addEventListener('click', () => {
                const details = button.nextElementSibling;
                details.style.display = details.style.display === 'none' ? 'block' : 'none';
                button.textContent = details.style.display === 'none' ? 'Show Details' : 'Hide Details';
            });
        });
    })
    .catch(error => {
        document.getElementById('results').textContent = 'An error occurred.';
        console.error(error);
    });
});
