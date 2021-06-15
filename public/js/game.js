function checkCanStart(usersOnline) {
    if (players.length === maxPlayers &&
        usersOnline === maxPlayers &&
        !started) {
        startGame();
    }
}

function requestCurrentGameView() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            document.getElementById('gameArea').innerHTML = response;
        }
    }

    xhr.open('POST', '/request-current-view');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
    }));
}

function checkMove() {
    switch (turnSequence) {
        case 1:

            break;
        case 2:

            break;
        case 3:

            break;
        case 4:

            break;
        case 5:
            requestPlotModal();
            break;
        case 6:
            
            break;
        default:
            break;
    }
}

async function startGame() {
    addToLog('Last player joined. Game starts in 10 seconds.');
    await sleep(1000 * 10);
    if (usersCount != maxPlayers) {
        addToLog('Game start cancelled. Player left the game.');
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

function requestPlotModal() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            document.getElementById('modal-content').innerHTML = response;

            if (!$('#plot-modal').hasClass('show')) {
                $('#plot-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#plot-modal').modal('show');
                return;
            }

            if (document.getElementById('plot-modal-selectionPlots').childElementCount == 0) {
                $('#plot-modal').on('hidden.bs.modal', function (e) {
                    window.requestPlotModal = function() {
                        return false;
                    }

                    document.getElementById('plot-modal').remove();
                })

                $("#plot-modal").modal("hide");
            }
        }
    }

    xhr.open('POST', '/request-plot-modal');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'user_id': userId,
        'game_id': id,
    }));
}

function pickCard(cardIndex, deck) {
    if (currentTurn['id'] != userId) {
        alert("It's not your turn");
        return;
    }

    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.open('POST', '/make-move');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
        'cardIndex': cardIndex,
        'deck': deck
    }));
};

function changeCurrentTurn() {
    document.getElementById('currentTurn').innerHTML = currentTurn.name;
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
    document.getElementById('log').innerHTML += "<p class='mb-0'>" + message + "</p>";
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
