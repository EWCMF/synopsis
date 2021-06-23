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

function requestPlotModal() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = async function () {
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

            if (maxPlayers == 1) {
                if (currentTurn['id'] == 'CPU') {


                    if (!cpuDebug) {
                        await sleep(1000 * 5);
                        requestCpuMove();
                    }


                }
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

function requestPlotResourceModal() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            document.getElementById('resource-modal-content').innerHTML = response;

            if (!$('#plot-resource-modal').hasClass('show')) {
                $('#plot-resource-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#plot-resource-modal').modal('show');
                return;
            }

            $('#plot-resource-modal').on('hidden.bs.modal', function (e) {
                document.getElementById('resource-modal-content').innerHTML = '';
            });
        }
    }

    xhr.open('POST', '/request-plot-resource-modal');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'user_id': userId,
        'game_id': id,
    }));
}

function requestPlotPurchaseModal(index) {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            document.getElementById('plot-purchase-modal-content').innerHTML = response;

            if (!$('#plot-purchase-modal').hasClass('show')) {
                $('#plot-purchase-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#plot-purchase-modal').modal('show');
                return;
            }

            $('#plot-purchase-modal').on('hidden.bs.modal', function (e) {
                document.getElementById('plot-purchase-modal-content').innerHTML = '';
            });
        }
    }

    xhr.open('POST', '/request-plot-purchase-modal');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'user_id': userId,
        'game_id': id,
        'cardIndex': index,
    }));
};

function requestBuildingPurchaseModal(index) {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {
            let response = xhr.responseText;
            document.getElementById('building-purchase-modal-content').innerHTML = response;

            if (!$('#building-purchase-modal').hasClass('show')) {
                $('#building-purchase-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('#building-purchase-modal').modal('show');
                return;
            }

            $('#building-purchase-modal').on('hidden.bs.modal', function (e) {
                document.getElementById('building-purchase-modal-content').innerHTML = '';
            });
        }
    }

    xhr.open('POST', '/request-building-purchase-modal');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'user_id': userId,
        'game_id': id,
        'cardIndex': index,
    }));
}

function pickCard(cardIndex, deck, option = 0) {
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
        'deck': deck,
        'option': option,
    }));

    document.getElementById('cardDescription').innerHTML = '';
};

function skipTurnSequence() {
    document.getElementById('cardDescription').innerHTML = '';

    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.open('POST', '/skip-move');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
    }));
};

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

function requestCpuMove() {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.open('POST', '/cpu-move');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
    }));
}
