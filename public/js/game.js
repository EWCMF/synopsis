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

            if (turnSequence == 6) {
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
            break;
        case 6:
            break;
        default:
            break;
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
    const log = document.getElementById('log');
    if (log.childElementCount == 8) {
        log.removeChild(log.children[0]);
    }
    log.innerHTML += "<p class='mb-0'>" + message + "</p>";
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

function showCard(html, card, selectable) {
    let newHtml;
    switch (card.type) {
        case "building":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "technology":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "unit":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "wonder":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "resource":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "plot":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "bonusResource":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "population":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        default:
            break;
    }

    if (currentTurn['id'] != userId) {
        return;
    }

    if (selectable) {
        if (document.getElementById('selectedCards').contains(html)) {
            document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="removeFromSelected('${html.id}')">Remove from selected</button>`
        } else {
            document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="addToSelected('${html.id}')">Add to selected</button>`
        }
    }
}

function addToSelected(htmlId) {
    document.getElementById('cardDescription').innerHTML = '';
    document.getElementById('selectedCards').appendChild(document.getElementById(htmlId));
    checkUseCards();
}

function removeFromSelected(htmlId) {
    document.getElementById('cardDescription').innerHTML = '';
    let node = document.getElementById(htmlId);
    document.getElementById('selectedCards').removeChild(node);
    document.getElementById('ownPlayCards').appendChild(node);
    checkUseCards();
}

function checkUseCards() {
    if (currentTurn['id'] != userId) {
        return;
    }
    let hasChildren = document.getElementById('selectedCards').childElementCount > 0;
    if (hasChildren) {
        if (!document.getElementById('useButton')) {
            let button = document.createElement('button');
            button.classList.add("btn", "btn-primary", "mt-3");
            button.id = 'useButton';
            button.onclick = useSelectedCards;
            button.textContent = "Use cards";
            document.getElementById('useButtonContainer').appendChild(button);
        }
    } else {
        document.getElementById('useButtonContainer').innerHTML = '';
    }
}

function useSelectedCards() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let cardIndexes = [];
    let selectedCards = document.getElementById('selectedCards').children
    let deck = document.getElementById('selectedCards').children[0].dataset.deck;
    let array = Array.from(selectedCards);
    array.forEach(element => {
        cardIndexes.push(element.dataset.index)
    });

    xhr.open('POST', '/make-moves');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
        'cardIndexes': JSON.stringify(cardIndexes),
        'deck': deck
    }));

    document.getElementById('selectedCards').innerHTML = '';
    document.getElementById('useButtonContainer').innerHTML = '';
}
