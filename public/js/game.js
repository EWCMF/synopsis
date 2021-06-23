function checkCanStart(usersOnline) {
    if (players.length === maxPlayers &&
        usersOnline === maxPlayers &&
        !started) {
        startGame();
    }
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

function changeCurrentTurn() {
    document.getElementById('currentTurn').innerHTML = currentTurn.name;
}

function changeTurnSequence() {
    document.getElementById('turnSequence').textContent = '';
    if (currentTurn['id'] != userId) {
        document.getElementById('turnSequence').textContent = 'Opponent turn'
        document.getElementById('options').innerHTML = ''
        return;
    }

    switch (turnSequence) {
        case 2:
            document.getElementById('turnSequence').textContent = 'Purchasing of cards';
            document.getElementById('options').innerHTML = '<button class="btn btn-primary" onclick="skipTurnSequence()">Next turn sequence</button>';
            break;

        case 3:
            document.getElementById('turnSequence').textContent = 'Combat';
            document.getElementById('options').innerHTML = '<button class="btn btn-primary" onclick="skipTurnSequence()">Next turn sequence</button>';
            break;

        case 4:
            document.getElementById('turnSequence').textContent = 'Draw and Discard';
            document.getElementById('options').innerHTML = '';
            break;

        case 5:
            document.getElementById('turnSequence').textContent = 'Select starting plots';
            document.getElementById('options').innerHTML = '';
            break;

        case 6:
            document.getElementById('turnSequence').textContent = 'Discard 2 cards';
            document.getElementById('options').innerHTML = '';
            break;

        default:
            break;
    }
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

function showCard(html, card, selectable) {
    let newHtml;
    switch (card.type) {
        case "Building":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Technology":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Unit":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Wonder":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Cost: ${card.cost}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Plot":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Bonus resource":
            newHtml = `<p>Name: ${card.name}</p><p>Type: ${card.type}</p><p>Special effect: ${card.specialEffect}</p>`;
            document.getElementById('cardDescription').innerHTML = newHtml;
            break;

        case "Population":
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
        if (turnSequence == 4 || turnSequence == 6) {
            if (document.getElementById('selectedCards').contains(html)) {
                document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="removeFromSelected('${html.id}')">Remove from selected</button>`
            } else {
                document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="addToSelected('${html.id}')">Add to selected</button>`
            }

            return;
        } else if (turnSequence == 3) {
            if (card.type == 'Unit') {
                if (document.getElementById('selectedCards').contains(html)) {
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="removeFromSelected('${html.id}')">Remove from selected</button>`
                } else {
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="addToSelected('${html.id}')">Add to selected</button>`
                }
            }

            return;
        }
    }

    switch (turnSequence) {
        case 2:
            if (card.type == 'Plot') {
                if (html.dataset.deck == 'purchaseablePlots') {
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="purchasePlot('${html.dataset.index}')">Purchase plot for ${checkPlotPrice()}</button>`
                } else if (html.dataset.deck == 'ownPlots') {
                    let price = checkPopulationPrice(html.dataset.index);
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="pickCard('${html.dataset.index}', '${html.dataset.deck}')">Purchase population for ${price}</button>`
                }
            } else if (card.type == 'Bonus resource') {
                if (card.specialEffectId == 7 || card.specialEffectId == 8) {
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="pickCard('${html.dataset.index}', 'bonusResource', 1)">Add to resources as commerce</button>`
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="pickCard('${html.dataset.index}', 'bonusResource', 2)">Add to resources as food</button>`
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="pickCard('${html.dataset.index}', 'bonusResource', 3)">Add to resources as production</button>`
                } else {
                    document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="pickCard('${html.dataset.index}', 'bonusResource')">Add to resources</button>`
                }
            } else if (card.type == 'Building' || card.type == 'Wonder') {
                document.getElementById('cardDescription').innerHTML += `<button class="btn btn-primary" onclick="purchaseBuilding('${html.dataset.index}')">Purchase ${card.type}</button>`
            }
            break;

        default:
            break;
    }
}

function checkPlotPrice() {
    return ownHand['plots'].length * 5 - 5;
}

function purchasePlot(index) {
    if (currentTurn['id'] != userId) {
        return;
    }

    let sum = ownHand['resources']['commerce'] + ownHand['resources']['food'] + ownHand['resources']['production']
    let needed = checkPlotPrice();

    if (sum < needed) {
        alert("You lack enough resources");
        return;
    }

    requestPlotPurchaseModal(index);
}

function purchaseBuilding(index) {
    if (currentTurn['id'] != userId) {
        return;
    }

    let production = +ownHand['resources']['production'];
    let needed = +ownHand['hand'][index]['cost'];
    let modifier = 0;

    if (production < needed + modifier) {
        alert("You lack enough production");
        return;
    }

    document.getElementById('cardDescription').innerHTML = '';

    requestBuildingPurchaseModal(index);
}

function checkPopulationPrice(cardIndex) {
    if (!ownHand.freePopUsed) {
        return 'free';
    }

    let base = 6;
    return ownHand['plots'][cardIndex]['attachedPopulation'] * 2 + base;
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
            let textContent = '';
            switch (turnSequence) {
                case 3:
                    textContent = 'Use for attack';
                    break;
                case 4:
                    textContent = 'Discard cards';
                case 6:
                    textContent = 'Discard cards';
                default:
                    break;
            }
            button.textContent = textContent;
            document.getElementById('useButtonContainer').appendChild(button);
        }
    } else {
        document.getElementById('useButtonContainer').innerHTML = '';
    }
}

function updateNotes() {
    document.getElementById('playerNotes').innerHTML = '';
    let html = '';
    for (const note of notes) {
        html = html.concat(`<p>${note}</p>`);
    }
    document.getElementById('playerNotes').innerHTML = html;
}


function addResourceModal(index, counterId) {
    let available = +document.getElementById('popAvail' + index).innerText;
    if (available == 0) {
        return;
    }

    available--
    document.getElementById('popAvail' + index).innerText = available;

    let counter = +document.getElementById(counterId).innerText;
    counter++;
    document.getElementById(counterId).innerText = counter;
    checkAllPopulationDistributed();
}

function subtractResourceModal(index, counterId) {
    let available = +document.getElementById('popAvail' + index).innerText;
    let max = +document.getElementById('popAvailMax' + index).innerText;
    if (available == max) {
        return;
    }

    available++
    document.getElementById('popAvail' + index).innerText = available;

    let counter = +document.getElementById(counterId).innerText;
    counter--;
    document.getElementById(counterId).innerText = counter;
    checkAllPopulationDistributed();
}

function checkAllPopulationDistributed() {
    let counters = document.getElementsByClassName('popCounter');
    let allUsed = true;

    for (const counter of counters) {
        let available = +counter.innerText;
        if (available == 0) {
            continue;
        } else {
            allUsed = false;
            break;
        }
    }

    if (allUsed) {
        document.getElementById('distButton').disabled = false;
    } else {
        document.getElementById('distButton').disabled = true;
    }
}

function comfirmDistribution() {
    let counters = document.getElementsByClassName('popCounter');
    let allUsed = true;

    for (const counter of counters) {
        let available = +counter.innerText;
        if (available == 0) {
            continue;
        } else {
            allUsed = false;
            break;
        }
    }

    if (allUsed) {
        document.getElementById('cardDescription').innerHTML = '';

        let xhr = new XMLHttpRequest();
        let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let food = +document.getElementById('foodCounter').innerText;
        let commerce = +document.getElementById('commerceCounter').innerText;
        let production = +document.getElementById('productionCounter').innerText;

        xhr.onload = function () {
            if (xhr.status == 200) {

                $('#plot-resource-modal').modal("hide");
            }
        }

        xhr.open('POST', '/add-resources');
        xhr.setRequestHeader("X-CSRF-Token", csrf);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            'game_id': id,
            'resources': {
                'food': food,
                'commerce': commerce,
                'production': production,
            }
        }));
    }
}

function addPurchaseModal(id) {
    let available = +document.getElementById(id).innerText;
    let resourcesAdded = +document.getElementById('resourcesAdded').innerText;
    let resourcesNeeded = +document.getElementById('resourcesNeeded').innerText;
    if (available == 0 || resourcesAdded == resourcesNeeded) {
        return;
    }

    available--
    document.getElementById(id).innerText = available;

    resourcesAdded++;
    document.getElementById('resourcesAdded').innerText = resourcesAdded;
    checkEnoughResourcesPlotPurchase();
}

function subtractPurchaseModal(id) {
    let available = +document.getElementById(id).innerText;
    let max = +document.getElementById(id + 'Max').innerText;
    let resourcesAdded = +document.getElementById('resourcesAdded').innerText;
    if (available == max) {
        return;
    }

    available++
    document.getElementById(id).innerText = available;

    resourcesAdded--;
    document.getElementById('resourcesAdded').innerText = resourcesAdded;
    checkEnoughResourcesPlotPurchase();
}

function checkEnoughResourcesPlotPurchase() {
    let resourcesAdded = +document.getElementById('resourcesAdded').innerText;
    let resourcesNeeded = +document.getElementById('resourcesNeeded').innerText;
    if (resourcesAdded == resourcesNeeded) {
        document.getElementById('plotDistButton').disabled = false;
        return true;
    } else {
        document.getElementById('plotDistButton').disabled = true;
        return false;
    }
}

function confirmPlotPurchase() {
    if (!checkEnoughResourcesPlotPurchase()) {
        return;
    }

    document.getElementById('cardDescription').innerHTML = '';

        let xhr = new XMLHttpRequest();
        let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let food = +document.getElementById('foodAvail').innerText;
        let commerce = +document.getElementById('commerceAvail').innerText;
        let production = +document.getElementById('productionAvail').innerText;

        xhr.onload = function () {
            if (xhr.status == 200) {

                $('#plot-purchase-modal').modal("hide");
            }
        }

        xhr.open('POST', '/purchase-plot');
        xhr.setRequestHeader("X-CSRF-Token", csrf);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            'game_id': id,
            'resources': {
                'food': food,
                'commerce': commerce,
                'production': production,
            },
            'cardIndex': document.getElementById('purchasedPlotIndex').value,
        }));
}
