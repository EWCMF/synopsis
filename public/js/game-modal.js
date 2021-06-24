function confirmBuildingOnPlot(plotIndex, buildingIndex) {
    let xhr = new XMLHttpRequest();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    xhr.onload = function () {
        if (xhr.status == 200) {

            $('#building-purchase-modal').modal("hide");
        }
    }

    xhr.open('POST', '/make-move');
    xhr.setRequestHeader("X-CSRF-Token", csrf);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        'game_id': id,
        'deck': 'building',
        'cardIndex': buildingIndex,
        'option': plotIndex,
    }));
}
