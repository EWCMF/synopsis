function serveCardHtml(card) {
    return "" +
    "<div class='card'>" +
        "<div class='card-body'>" +
            "<h5 class='card-title'>Card title</h5>" +
            "<p class='card-text'>Example</p>" +
         "</div>" +
    "</div>"
}

function serveCardHtml(card) {
    return `<p class="m-0"><a class="text-light">${card.name}: ${card.specialEffect}</a></p>`;
}

function serveSelectablePlotHtml(card, index) {
    return `<p id="selectablePlot${index}" class="m-0" onclick="pickCard(${index}, 'purchaseablePlots')"><a class="text-light">${card.name}: ${card.specialEffect}</a></p>`;
}
