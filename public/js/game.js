function checkCanStart(usersOnline) {

    if (state.players.length === maxPlayers &&
        usersOnline === maxPlayers &&
        !started) {
        startGame();
    }
}

async function startGame() {
    addToLog('Last player joined. Game starts in 10 seconds.');
    await sleep(1000 * 10);
    if (usersCount != maxPlayers) {
        addToLog('Game start canceled. Player left the game.');
        return;
    }

    if (userId === ownerId && !gameStarting) {
        gameStarting = true;
        let xhr = new XMLHttpRequest();
        let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        xhr.open('POST', '/start-game');
        xhr.setRequestHeader("X-CSRF-Token", csrf);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            'user_id': userId,
            'game_id': id,
        }));
    }
}

function initialGameState() {
    document.getElementById('plot-modal-currentTurn').innerHTML = "Current turn: " + state.currentTurn.name;
    for (const player of state.players) {
        if (player.id == userId) {
            for (const plot of state.cardsOnHand[userId]) {
                document.getElementById('plot-modal-ownPlots').innerHTML += servePlotHtml(plot);
            }
        } else {
            for (const plot of state.cardsOnHand[player.id]) {
                document.getElementById('plot-modal-foePlots').innerHTML += servePlotHtml(plot);
            }
        }
    }

    for (const plot of state.purchaseablePlots) {
        document.getElementById('plot-modal-selectionPlots').innerHTML += servePlotHtml(plot);
    }

    $('#plot-modal').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#plot-modal').modal('show');
}

function changeCurrentTurn() {
    document.getElementById('currentTurn').innerHTML = state.currentTurn.name;
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function updatePlayers(users) {
    let players = document.getElementById('players');
    let innerHtml = '';
    for (const user of users) {
        innerHtml += "<p>" + user.name + "</p>"
    }
    players.innerHTML = innerHtml;
}

function updateOnline(users) {
    let players = document.getElementById('online');
    let innerHtml = '';
    for (const user of users) {
        innerHtml += "<p>" + user.name + "</p>"
    }
    players.innerHTML = innerHtml;
}

function addToLog(message) {
    document.getElementById('log').innerHTML += "<p>" + message + "</p>";
}

function updatePlayerStatusInDB(userId, isPlaying) {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.open('POST', '/change-playing-state');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'user_id': userId,
        'game_id': id,
        'isPlaying': isPlaying
    }));
}
